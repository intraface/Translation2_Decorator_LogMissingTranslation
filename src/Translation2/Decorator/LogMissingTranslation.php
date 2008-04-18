<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Contains the Translation2_Decorator_LogMissingTranslation class
 *
 * PHP versions 4 and 5
 *
 * @category   Internationalization
 * @package    Translation2
 * @author     Sune Jensen (sj@sunet.dk)
 * @copyright  2007 Sune Jensen
 * @license    http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
 * @version    @package-version@
 * @link       http://pear.php.net/package/Translation2
 */

/**
 * Load Translation2 decorator base class
 */
require_once 'Translation2/Decorator.php';

/**
 * Decorator to log if a translation returns an empty string.
 *
 * @category   Internationalization
 * @package    Translation2
 * @author     Sune Jensen <sj@sunet.dk>
 * @copyright  2007 Sune Jensen
 * @license    http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
 * @version    @package-version@
 * @link       http://pear.php.net/package/Translation2
 */
class Translation2_Decorator_LogMissingTranslation extends Translation2_Decorator
{
    // @todo should be set outside class
    // actually it would be clever that you could just add observers
    var $error_log = ERROR_LOG;

    /**
     * Get translated string
     *
     * If the string is empty write it to a log
     *
     * @param string $stringID
     * @param string $pageID
     * @param string $langID
     * @param string $defaultText Text to display when the string is empty
     *
     * @return string
     */
    function get($stringID, $pageID=TRANSLATION2_DEFAULT_PAGEID, $langID=null, $defaultText='')
    {
        $str = $this->translation2->get($stringID, $pageID, $langID, $defaultText);
        if (empty($str)) {
            $this->writeLog('Missing translation for "'.$stringID.'" on pageID: "'.$pageID.'", LangID: "'.$langID.'"');
        }
        return $str;
    }

    /**
     * Writes to a log file
     *
     * @param string $error Error message to log
     *
     * @return void
     */
    function writeLog($err) {
        
        if(isset($this->logger) && is_callable($this->logger)) {
            $details = array(
                'date' => date('r'),
                'type' => 'Translation2',
                'message' => $err,
                'file' => '[unknown]',
                'line' => '[unknown]'
            );
            call_user_func($this->logger, $details);
        } else {
            return PEAR::raiseError('No logger was found. Please define a logger by setOption("logger", mixed var)');
        }
    }
    
    /**
     * set Decorator option (intercept 'fallbackLang' option).
     * I don't know why it's needed, but it doesn't work without.
     * 
     *
     * @param string option name
     * @param mixed  option value
     */
    
    function setOption($option, $value=null)
    {
        if ($option == 'logger') {
            $this->logger = $value;
        } else {
            parent::setOption($option, $value);
        }
    }

}
?>