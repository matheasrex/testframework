<?

namespace Framework;

/**
 * this class is responsible for translation
 */
class Translator
{
	/**
	 * @var array $cache Store the translation values
	 */
	protected $cache = array();
	/**
	 * @var string $filePath Path of language files
	 */
	protected $filePath = '';
	
	/**
	 * global contructor
	 *
	 * @param string $languageFilePath Path of the lang files
	 */
	public function __construct($languageFilePath)
	{
		$this->filePath = $languageFilePath;
	}
	/**
	 * function to translate
	 *
	 * @param string $label Translator label
	 * @param string $type  Translator type
	 *
	 * @return string Translated label
	 */
	public function translate($label, $type)
	{
		if (!isset($this->cache[$type])) {
			$this->generateCache($type);
		}
		if (isset($this->cache[$type][$label])) {
			return $this->cache[$type][$label];
		}
		
		return '#'.$label.'#';
	}
	/**
	 * function to generate one type of cache
	 *
	 * @param string $type Translator type
	 */
	protected function generateCache($type)
	{
		$filename = $this->filePath.'/'.$type.'.php';
		$this->cache[$type] = include($filename);
	}
}
