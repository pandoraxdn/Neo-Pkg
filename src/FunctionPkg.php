<?php

namespace beatstar\pkg;

use beatstar\pkg\Jwt\Cryptography\Algorithms\Hmac\HS384;

use beatstar\pkg\Jwt\Exceptions\InvalidKeyException;

use beatstar\pkg\Jwt\Exceptions\InvalidSignatureException;

use beatstar\pkg\Jwt\Exceptions\InvalidTokenException;

use beatstar\pkg\Jwt\Exceptions\JsonDecodingException;

use beatstar\pkg\Jwt\Exceptions\JsonEncodingException;

use beatstar\pkg\Jwt\Exceptions\SigningException;

use beatstar\pkg\Jwt\Exceptions\ValidationException;

use beatstar\pkg\GeneratorPkg;

use beatstar\pkg\ParserPkg;

use DateTime;

class FunctionPkg
{
	private $hello, $string, $object, $encryption, $decryption, $jwt, $claims;

	public function hello()
	{
		$hello = "Hello Pkg";

		return $hello;
	}

	public function current_day()
    {
    	$string = $this->encrypt(date_format(new DateTime('now'),'d/m/Y H:i:s'));

    	return $string;

    }

    public function format_date()
    {
        $string = date_format(new DateTime('now'),'d/m/Y H:i:s');

        return $string;
    }

    public function request_date(string $time)
    {
        $string = $this->encrypt(date_format(new DateTime($time),'d/m/Y H:i:s'));

        return $string;
    }

    public function create_format(string $time)
    {
        $object = date_create_from_format('d/m/Y H:i:s', $time);

        return $object;
    }

    public function encrypt(string $value){

        $ciphering = "AES-256-CBC"; 

        $iv_length = openssl_cipher_iv_length($ciphering);

        $options = 0;

        $encryption_iv = '8AC7230489E80000';

        $encryption_key = config("neo-pkg.secret");

        $encryption = openssl_encrypt($value, $ciphering, $encryption_key, $options, $encryption_iv);

        return $encryption;

    }

    public function decrypt(string $value)
    {

        try {

            $ciphering = "AES-256-CBC";  

            $iv_length = openssl_cipher_iv_length($ciphering);

            $options = 0;

            $encryption_iv = '8AC7230489E80000';

            $encryption_key = config("neo-pkg.secret");

            $decryption = openssl_decrypt($value, $ciphering, $encryption_key, $options, $encryption_iv);
            
        } catch (Exception $e) {

            $decryption = null;
        }

        return $decryption;

    }

    public function generate_token(array $claims = []): string
    {
        $signer = new HS384(config("neo-pkg.secret"));

        $generator = new GeneratorPkg($signer);

        $jwt = $generator->generate($claims);

        return $jwt;
    }

    public function parse_token($value)
    {
        try {

            $signer = new HS384(config("neo-pkg.secret"));

            $parser = new ParserPkg($signer);

            $claims = $parser->parse($value);
            
        }catch (InvalidKeyException $e) {

            $claims = null;

        }catch (InvalidSignatureException $e) {

            $claims = null;

        }catch (InvalidTokenException $e) {

            $claims = null;

        }catch (JsonDecodingException $e) {

            $claims = null;

        }catch (JsonEncodingException $e) {

            $claims = null;

        }catch (SigningException $e) {

            $claims = null;

        }catch (ValidationException $e) {

            $claims = null;

        }

        return $claims;
    }
}