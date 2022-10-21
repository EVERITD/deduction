<?php
/**
 * Ever_Encrypt
 * 
 * @package Bill Counting - Protected
 * @author  everitd - class from codeigniter
 * @copyright 2010
 * @version $Id$
 * @access public
 */
class Ever_Encrypt{
	var $ever;
	var $encryption_key	= 'everitd';
	var $_hash_type	= 'sha1';
	var $_mcrypt_exists = FALSE;
	var $_mcrypt_cipher;
	var $_mcrypt_mode;
    
	/**
	 * Ever_Encrypt::Ever_Encrypt()
	 * 
	 * @return
	 */
	function Ever_Encrypt()
	{
		$this->_mcrypt_exists = ( ! function_exists('mcrypt_encrypt')) ? FALSE : TRUE;
	}
    
	/**
	 * Ever_Encrypt::get_key()
	 * 
	 * @param string $key
	 * @return
	 */
	function get_key($key = '')
	{
		if ($key == '')
		{
			if ($this->encryption_key != '')
			{
				return $this->encryption_key;
			}
		}

		return md5($key);
	}

	/**
	 * Ever_Encrypt::encode()
	 * 
	 * @param mixed $string
	 * @param string $key
	 * @return
	 */
	function encode($string, $key = '')
	{
		$key = $this->get_key($key);                  // convert the key to md5($key)
		$enc = $this->_xor_encode($string, $key);     //
		
		if ($this->_mcrypt_exists === TRUE)
		{
			$enc = $this->mcrypt_encode($enc, $key);
		}
		return base64_encode($enc);
	}

	/**
	 * Ever_Encrypt::_xor_encode()
	 * 
	 * @param mixed $string
	 * @param mixed $key
	 * @return
	 */
	function _xor_encode($string, $key)
	{
		$rand = '';
		while (strlen($rand) < 32)
		{
			$rand .= mt_rand(0, mt_getrandmax());    // get the largest possible value        
		}

		$rand = $this->hash($rand);                  // execute the sha1

		$enc = '';
		for ($i = 0; $i < strlen($string); $i++)      // this part generates the random value
		{			
			$enc .= substr($rand, ($i % strlen($rand)), 1).(substr($rand, ($i % strlen($rand)), 1) ^ substr($string, $i, 1));
		}

		return $this->_xor_merge($enc, $key);
	}

	/**
	 * Ever_Encrypt::mcrypt_encode()
	 * 
	 * @param mixed $data
	 * @param mixed $key
	 * @return
	 */
	function mcrypt_encode($data, $key)
	{
		$init_size = mcrypt_get_iv_size($this->_get_cipher(), $this->_get_mode());
		$init_vect = mcrypt_create_iv($init_size, MCRYPT_RAND);
		return $this->_add_cipher_noise($init_vect.mcrypt_encrypt($this->_get_cipher(), $key, $data, $this->_get_mode(), $init_vect), $key);
	}

	/**
	 * Ever_Encrypt::hash()
	 * 
	 * @param mixed $str
	 * @return
	 */
	function hash($str)
	{
		return ($this->_hash_type == 'sha1') ? $this->sha1($str) : md5($str);
	}
    
	/**
	 * Ever_Encrypt::_xor_merge()
	 * 
	 * @param mixed $string
	 * @param mixed $key
	 * @return
	 */
	function _xor_merge($string, $key)
	{
		$hash = $this->hash($key);
		$str = '';
		for ($i = 0; $i < strlen($string); $i++)
		{
			$str .= substr($string, $i, 1) ^ substr($hash, ($i % strlen($hash)), 1);
		}

		return $str;
	}

	/**
	 * Ever_Encrypt::_get_cipher()
	 * 
	 * @return
	 */
	function _get_cipher()
	{
		if ($this->_mcrypt_cipher == '')
		{
			$this->_mcrypt_cipher = MCRYPT_RIJNDAEL_256;
		}

		return $this->_mcrypt_cipher;
	}

	/**
	 * Ever_Encrypt::_get_mode()
	 * 
	 * @return
	 */
	function _get_mode()
	{
		if ($this->_mcrypt_mode == '')
		{
			$this->_mcrypt_mode = MCRYPT_MODE_ECB;
		}
		
		return $this->_mcrypt_mode;
	}

	/**
	 * Ever_Encrypt::_add_cipher_noise()
	 * 
	 * @param mixed $data
	 * @param mixed $key
	 * @return
	 */
	function _add_cipher_noise($data, $key)
	{
		$keyhash = $this->hash($key);
		$keylen = strlen($keyhash);
		$str = '';

		for ($i = 0, $j = 0, $len = strlen($data); $i < $len; ++$i, ++$j)
		{
			if ($j >= $keylen)
			{
				$j = 0;
			}

			$str .= chr((ord($data[$i]) + ord($keyhash[$j])) % 256);
		}

		return $str;
	}

// decode
	/**
	 * Ever_Encrypt::decode()
	 * 
	 * @param mixed $string
	 * @param string $key
	 * @return
	 */
	function decode($string, $key = '')
	{
		$key = $this->get_key($key);
		
		if (preg_match('/[^a-zA-Z0-9\/\+=]/', $string))
		{
			return FALSE;
		}

		$dec = base64_decode($string);

		if ($this->_mcrypt_exists === TRUE)
		{
			if (($dec = $this->mcrypt_decode($dec, $key)) === FALSE)
			{
				return FALSE;
			}
		}

		return $this->_xor_decode($dec, $key);
	}
    
	/**
	 * Ever_Encrypt::mcrypt_decode()
	 * 
	 * @param mixed $data
	 * @param mixed $key
	 * @return
	 */
	function mcrypt_decode($data, $key)
	{
		$data = $this->_remove_cipher_noise($data, $key);
		$init_size = mcrypt_get_iv_size($this->_get_cipher(), $this->_get_mode());

		if ($init_size > strlen($data))
		{
			return FALSE;
		}

		$init_vect = substr($data, 0, $init_size);
		$data = substr($data, $init_size);
		return rtrim(mcrypt_decrypt($this->_get_cipher(), $key, $data, $this->_get_mode(), $init_vect), "\0");
	}

	/**
	 * Ever_Encrypt::_remove_cipher_noise()
	 * 
	 * @param mixed $data
	 * @param mixed $key
	 * @return
	 */
	function _remove_cipher_noise($data, $key)
	{
		$keyhash = $this->hash($key);
		$keylen = strlen($keyhash);
		$str = '';

		for ($i = 0, $j = 0, $len = strlen($data); $i < $len; ++$i, ++$j)
		{
			if ($j >= $keylen)
			{
				$j = 0;
			}

			$temp = ord($data[$i]) - ord($keyhash[$j]);

			if ($temp < 0)
			{
				$temp = $temp + 256;
			}
			
			$str .= chr($temp);
		}

		return $str;
	}
    
	/**
	 * Ever_Encrypt::_xor_decode()
	 * 
	 * @param mixed $string
	 * @param mixed $key
	 * @return
	 */
	function _xor_decode($string, $key)
	{
		$string = $this->_xor_merge($string, $key);

		$dec = '';
		for ($i = 0; $i < strlen($string); $i++)
		{
			$dec .= (substr($string, $i++, 1) ^ substr($string, $i, 1));
		}

		return $dec;
	}

	/**
	 * Ever_Encrypt::sha1()
	 * 
	 * @param mixed $str
	 * @return
	 */
	function sha1($str)
	{
		if ( ! function_exists('sha1'))
		{
			if ( ! function_exists('mhash'))
			{
				require_once(BASEPATH.'libraries/Sha1'.EXT);
				$SH = new CI_SHA;
				return $SH->generate($str);
			}
			else
			{
				return bin2hex(mhash(MHASH_SHA1, $str));
			}
		}
		else
		{
			return sha1($str);
		}
	}

}

?>