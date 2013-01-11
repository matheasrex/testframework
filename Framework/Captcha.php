<?

namespace Framework;

/**
 * A simple PHP CAPTCHA script
 *
 * Copyright 2011 by Cory LaViska for A Beautiful Site, LLC.
 *
 * http://abeautifulsite.net/blog/2011/01/a-simple-php-captcha-script/
 *
 * comment by matheasrex:
 * it was so ugly, I had to rewrite it a little. It's still ugly, but working
 */
class Captcha
{
	/**
	 * @var array $config Configuration list
	 */
	protected $config = array(
		'code' => '',
		'min_length' => 5,
		'max_length' => 5,
		'png_background' => 'default_captcha.png',
		'font' => 'times_new_yorker.ttf',
		'characters' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',
		'min_font_size' => 24,
		'max_font_size' => 30,
		'color' => '#000',
		'angle_min' => 0,
		'angle_max' => 15,
		'shadow' => true,
		'shadow_color' => '#CCC',
		'shadow_offset_x' => -2,
		'shadow_offset_y' => 2,
		'font_path' => '',
		'image_path' => '',
	);
	/**
	 * @var \Framework\Request $request Request object
	 */
	protected $request;
	
	/**
	 * global constructor
	 *
	 * @param \Framework\Request $request Request object
	 */
	public function __construct(\Framework\Request &$request)
	{
		$this->request = &$request;
		
		$config = $this->request->configuration->get('captcha.config', array());
		foreach ($config as $key => $value) {
			$this->config[$key] = $value;
		}
	}
	
	/**
	 * validate capcha
	 *
	 * @return bool
	 */
	public function validate()
	{
		if (!$this->request->request->get('capcha_code', '')) {
			return false;
		}
		
		return $this->request->request->get('capcha_code') == $this->request->session->get('capcha_code');
	}
	
	/**
	 * draw capcha image - this is the legacy looking ugly function
	 * it's third party.
	 *
	 * @return resource
	 */
	public function draw()
	{
		$code = $this->generateCode();

		srand(microtime() * 100);

		$background = $this->config['image_path'].'/'.$this->config['png_background'];
		list($bg_width, $bg_height, $bg_type, $bg_attr) = getimagesize($background);

		$captcha = imagecreatefrompng($background);
		imagealphablending($captcha, true);
		imagesavealpha($captcha, true);

		$color = $this->hex2rgb($this->config['color']);
		$color = imagecolorallocate($captcha, $color['r'], $color['g'], $color['b']);

		$angle = rand($this->config['angle_min'], $this->config['angle_max']) * (rand(0, 1) == 1 ? -1 : 1);
		
		$font = $this->config['font_path'].'/'.$this->config['font'];

		if (!file_exists($font)) {
			throw new \InvalidArgumentException('Font file not found: ' . $font);
		}

		//Set the font size.
		$font_size = rand($this->config['min_font_size'], $this->config['max_font_size']);
		$text_box_size = imagettfbbox($font_size, $angle, $font, $code);

		// Determine text position
		$box_width = abs($text_box_size[6] - $text_box_size[2]);
		$box_height = abs($text_box_size[5] - $text_box_size[1]);
		$text_pos_x_min = 0;
		$text_pos_x_max = ($bg_width) - ($box_width);
		$text_pos_x = rand($text_pos_x_min, $text_pos_x_max);
		$text_pos_y_min = $box_height;
		$text_pos_y_max = ($bg_height) - ($box_height / 2);
		$text_pos_y = rand($text_pos_y_min, $text_pos_y_max);

		// Draw shadow
		if ($this->config['shadow']) {
			$shadow_color = $this->hex2rgb($this->config['shadow_color']);
			$shadow_color = imagecolorallocate($captcha, $shadow_color['r'], $shadow_color['g'], $shadow_color['b']);
			imagettftext($captcha, $font_size, $angle, $text_pos_x + $this->config['shadow_offset_x'], $text_pos_y + $this->config['shadow_offset_y'], $shadow_color, $font, $code);
		}

		imagettftext($captcha, $font_size, $angle, $text_pos_x, $text_pos_y, $color, $font, $code);

		return $captcha;
	}
	
	/**
	 * output capcha
	 *
	 * @param resource $image Generated image
	 */
	public function output($image)
	{
		header("Content-type: image/png");
		imagepng($image);
		exit;
	}
	
	/**
	 * funtion to generate code if not set
	 * and also store it to session
	 *
	 * @return code
	 */
	protected function generateCode()
	{
		srand(microtime() * 100);
		$code = '';
		$length = rand($this->config['min_length'], $this->config['max_length']);
		while (strlen($code) < $length) {
			$code .= substr(
				$this->config['characters'], 
				rand() % (strlen($this->config['characters'])),
				1
			);
		}
		$this->config['code'] = $code;
		$this->request->session->set('capcha_code', $code);
		
		return $code;
	}
	/**
	 * Convert a hexa decimal color code to its RGB equivalent
	 *
	 * @param string $hexStr       Hexadecimal color value
	 * @param bool   $returnString If set true, returns the value separated by the separator character. Otherwise returns assoc array
	 * @param string $separator    To separate RGB values. Applicable only if second parameter is true.
	 * 
	 * @return array or string Depending on second parameter. Returns False if invalid hex color value
	 */      
	protected function hex2rgb($hexStr, $returnString = false, $separator = ',')
	{
		$hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); 
		$rgbArray = array();
		if (strlen($hexStr) == 6) {
			$colorVal = hexdec($hexStr);
			$rgbArray['r'] = 0xFF & ($colorVal >> 0x10);
			$rgbArray['g'] = 0xFF & ($colorVal >> 0x8);
			$rgbArray['b'] = 0xFF & $colorVal;
		} elseif (strlen($hexStr) == 3) {
			$rgbArray['r'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
			$rgbArray['g'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
			$rgbArray['b'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
		} else {
			throw new \InvalidArgumentException($hexStr.' is not a valid color definition');
		}
		
		return $returnString ? implode($separator, $rgbArray) : $rgbArray;
	}
}
