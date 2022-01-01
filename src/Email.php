<?php

namespace Choval;

class Email
{
    protected $raw;

    public function __construct(string $email)
    {
        $this->raw = trim($email);
    }

    public function valid()
    {
        $p = $this->parse();
        if ($p['valid']) {
            return $p['email'];
        }
        return false;
    }

    public function parse()
    {
        $tmp = [];
        $tmp['raw'] = $this->raw;
        $tmp['unicode'] = false;
        $tmp['valid'] = false;
        if (preg_match('/^(?P<local>[^\.\x{3002}\s@][^@\s]*)@(?P<domain>([^@\s\.\x{3002}]+[\.\x{3002}])+[^@\s\.\x{3002}]+)$/u', $this->raw, $match)) {
            if (preg_match('/\((?<comment>.+)\)/Uu', $match['local'], $match_comment)) {
                $tmp['comment'] = $match_comment['comment'];
                $match['local'] = str_replace($match[0], '', $match['local']);
            }
            $tmp['idn_domain'] = strtolower(idn_to_ascii($match['domain'], \IDNA_DEFAULT, \INTL_IDNA_VARIANT_UTS46));
            $tmp['idn_local'] = strtolower(idn_to_ascii($match['local'], \IDNA_DEFAULT, \INTL_IDNA_VARIANT_UTS46));
            $domain_parts = explode('.', $tmp['idn_domain']);
            $tmp['idn_tld'] = end($domain_parts);
            $tmp['domain'] = idn_to_utf8($tmp['idn_domain'], 0, \INTL_IDNA_VARIANT_UTS46);
            $tmp['tld'] = idn_to_utf8($tmp['idn_tld'], 0, \INTL_IDNA_VARIANT_UTS46);
            $tmp['local'] = idn_to_utf8($tmp['idn_local'], 0, \INTL_IDNA_VARIANT_UTS46);
            $tmp['unicode'] = ($tmp['idn_domain'] != $tmp['domain'] || $tmp['idn_local'] != $tmp['local']) ? true : false;
            $tmp['safe_email'] = $tmp['idn_local'] . '@' . $tmp['idn_domain'];
            $tmp['email'] = $tmp['local'] . '@' . $tmp['domain'];
            $tmp['valid'] = filter_var($tmp['safe_email'], \FILTER_VALIDATE_EMAIL) ? true : false;
        }
        return $tmp;
    }

    public function safe()
    {
        $p = $this->parse();
        if ($p['valid']) {
            return $p['safe_email'];
        }
        return false;
    }
}
