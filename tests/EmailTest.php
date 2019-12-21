<?php

use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function emailProvider()
    {
        $raws = "
Abc@example.com
Abc.123@example.com
user+mailbox/department=shipping@example.com
!#$%&'*+-/=?^_`.{|}~@example.com
用户@例子.广告
अजय@डाटा.भारत   
квіточка@пошта.укр
θσερ@εχαμπλε.ψομ
Dörte@Sörensen.example.COM
коля@пример.рф
china@email。com
";

        $bads = "
abademail@nodomain
.nonvalid@EMAIL
asd@asd@asd.com
two@three..com
";
        $emails = explode("\n", $raws);
        $out = [];
        foreach ($emails as $email) {
            $email = trim($email);
            if ($email) {
                $out[] = [$email, true];
            }
        }
        $bads = explode("\n", $bads);
        foreach ($bads as $email) {
            $email = trim($email);
            if ($email) {
                $out[] = [$email, false];
            }
        }
        return $out;
    }



    /**
     * @dataProvider emailProvider
     */
    public function testParse($email, $valid)
    {
        $parts = email_parse($email);
        $this->assertArrayHasKey('raw', $parts);
        $this->assertEquals($email, $parts['raw']);
        $cols = ['idn_domain', 'idn_local', 'local', 'domain', 'safe_email', 'email'];
        var_dump($parts);
        $this->assertEquals($valid, $parts['valid']);
        if ($valid) {
            foreach ($cols as $col) {
                $this->assertArrayHasKey($col, $parts);
            }
            $this->assertNotEmpty(filter_var($parts['safe_email'], FILTER_VALIDATE_EMAIL));
        } else {
            foreach ($cols as $col) {
                $this->assertArrayNotHasKey($col, $parts);
            }
        }
    }
}
