<?php
namespace {
    use Choval\Email;

    function email_parse(string $email)
    {
        $obj = new Email($email);
        return $obj->parse();
    }

    function email_safe(string $email)
    {
        $obj = new Email($email);
        return $obj->safe();
    }

    function email_valid(string $email)
    {
        $obj = new Email($email);
        return $obj->valid();
    }

}
namespace Choval\Email {
    use Choval\Email;

    function email_parse(string $email)
    {
        $obj = new Email($email);
        return $obj->parse();
    }

    function email_safe(string $email)
    {
        $obj = new Email($email);
        return $obj->safe();
    }

    function email_valid(string $email)
    {
        $obj = new Email($email);
        return $obj->valid();
    }

}
