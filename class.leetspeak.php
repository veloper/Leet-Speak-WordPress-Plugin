<?php
/**
* Main Class for Leet Speak WordPress Plugin
*
* @copyright Copyright (c) 2010 Daniel Doezema. (http://dan.doezema.com)
* @license http://dan.doezema.com/licenses/new-bsd New BSD License
*/
class DDLeetSpeak {
    /**
     *  Stores the leet speak alphabet translations. array('A'=>'@', ...);
     *
     * @var array
     */
    private $alaphabet = array();

    /**
     * Characters pre/appended to [a-zA-Z] in order to
     * prevent translation conflicts.
     *
     * @var array
     */
    private $non_conflict = array('prepend'=>'[[', 'append'=>']]');

    /**
     * Class Constructor
     *
     * @return void
     */
    public function __construct() {
        $this->alaphabet = is_array($alphabet = $this->getAlphabet()) ? $alphabet : $this->getDefaultAlphabet();
    }

    /**
     * Converts a string into leet speak.
     *
     * @param string
     * @return string
     */
    public function leetize($string) {
    // Attempt to get all text (words) between HTML tags.
        $words = explode(' ', strip_tags(str_replace(array("\r","\n","\t"),' ',$string)));
        // Filter out any blank elements
        foreach($words as $key => $word) {
            if(strlen(trim($word)) < 1) {
                unset($words[$key]);
            }
        }
        // Remove Duplicates
        $words = array_unique($words);
        // Break word up into find and replace pairs.
        $find_and_replace = array();
        foreach($words as $word) {
            $find_and_replace[trim($word)] = $this->nonConflictToLeet($this->stringToNonConflict($word));
        }
        // Convert original string to leet speak
        return strtr($string, $find_and_replace);
    }

    /**
     * Gets the translation alphabet from the database.
     *
     * @return mixed; array, bool FALSE if no results found or database error.
     */
    public function getAlphabet() {
        // This done via a direct database call as there would be 26 seperate queries done though get_option()
        global $wpdb;
        $result = $wpdb->get_results('SELECT option_name, option_value FROM '.$wpdb->prefix.'options WHERE option_name LIKE "ddleetspeak_%"', ARRAY_A);
        if(is_array($result) && count($result) > 0) {
            $alphabet = array();
            foreach($result as $row) {
                if(preg_match('!ddleetspeak_[a-z]{1}!', $row['option_name'])) {
                    $alphabet[substr($row['option_name'], -1, 1)] = $row['option_value'];
                }
            }
            return $alphabet;
        }
        return false;
    }

    /**
     * Get the default alphabet array or a specific character from default translation alphabet.
     *
     * @param string
     * @return mixed; array, string if a translation for $specific_character was found
     */
    public function getDefaultAlphabet($specific_character = null) {
        $chars = array('a' => '@', 'b' => '|3', 'c' => '(', 'd' => '|)', 'e' => '3', 'f' => 'ph',
            'g' => '6',  'h' => ']-[',  'i' => '!', 'j' => '_/', 'k' => '|{', 'l' => '1',
            'm' => '|V|', 'n' => '/\/', 'o' => '0', 'p' => '|*', 'q' => '(_,)', 'r' => '|2',
            's' => '5', 't' => '7', 'u' => '|_|', 'v' => '\/',  'w' => '\/\/',  'x' => '><',
            'y' => '`/', 'z' => '2');
        return (is_string($specific_character) && in_array(strtolower($specific_character), array_keys($chars))) ? $chars[$specific_character] : $chars;
    }

    /**
     * Convert non-conflict characters ([[a]], [[b]]) into leet characters (@, |3)
     *
     * @param string
     * @return string
     */
    private function nonConflictToLeet($non_conflict) {
        $alphabet = $this->alaphabet;
        foreach($alphabet as $reg_char => $leet_char) $non_conflict = str_ireplace($this->non_conflict['prepend'].$reg_char.$this->non_conflict['append'] , $leet_char, $non_conflict);
        return $non_conflict;
    }

    /**
     * Convert a string's alpha characters into non-conflicting characters.
     *
     * @param string
     * @return string
     */
    private function stringToNonConflict($string) {
        return preg_replace('!([a-z]{1}?)!i',$this->non_conflict['prepend'].'$1'.$this->non_conflict['append'],$string);
    }

    /**
     * Get suggested leet characters alphabet arrays, or only
     * suggestions for specific character.
     *
     * @param string
     * @return array
     */
    public function getLeetCharacters($specific_character = null) {
        $chars = array();
        $chars['a'] = array('4','/\\', '@', '/-\\', '^', 'aye', 'ci', '&lambda;');
        $chars['b'] = array('6', '8', '13', 'I3', '|3', '&beta;', 'P>', '|:', '!3', '(3', '/3', ')3', ']3');
        $chars['c'] = array('&cent;', '<', '(', '{', '&copy;');
        $chars['d'] = array('|)', '|o', '&part;', '])', '[)', 'I>', '|>', 'T)', 'cl');
        $chars['e'] = array('3', '&amp;', '&euro;', '&pound;', '[-');
        $chars['f'] = array(']=', '|=', '(=', 'I=');
        $chars['g'] = array('6', '(_+', '9', 'C-', '(_-');
        $chars['h'] = array('/-/', '[-]', ']-[', ')-(', '(-)', ':-:', '|~|', '|-|', ']~[', '}{', '}-{', '#');
        $chars['i'] = array('!', '|', 'eye', '3y3', '][', ':', ']');
        $chars['j'] = array('_|', '_/', ']', '&iexcl;', '</', '(/');
        $chars['k'] = array('|X', '|<', '|{');
        $chars['l'] = array('1', '&pound;', '1_', '|', '|_');
        $chars['m'] = array('|v|', ']V[', '(T)', '[V]', '//\\//\\\\', '|\/|', '/\/\\', '(u)', '(V)', '(\/)', '/|\\', '^^', '/|/|', '//.', '.\\', '/^^\\', '/V\\', '[]\/[]', '|^^|');
        $chars['n'] = array('|\|', '^/', '//\\//', '/\/', '[\]', '<\>', '{\}', '[]\\', '/V', '[]\[]', ']\[', '~');
        $chars['o'] = array('0', '()', '[]', '&curren;', '&Omega;');
        $chars['p'] = array('|*', '|o', '|&deg;', '|^(o)', '|>', '|"', '9', '[]D', '|̊', '|7', '&para;');
        $chars['q'] = array('(_,)', '()_', '0_', '<|', '9', 'O,');
        $chars['r'] = array('2', '12', '|?', '/2', 'I2', '|^', '|~', 'lz', '&reg;', '|2', '[z', '|`', 'l2');
        $chars['s'] = array('5', '$', 'z', '&sect;');
        $chars['t'] = array('7', '+', '-|-', "']['", '&dagger;');
        $chars['u'] = array('|_|','&mu;');
        $chars['v'] = array('\/', '&radic;', '\\//');
        $chars['w'] = array('\/\/', 'vv', '\'//', '\\\'', '\^/', '(n)', '\V/', '\X/', '\|/', '\_|_/', '\\//\\//', '\_:_/', '(/\)', ']I[', 'LL1', 'UU', '&psi;', '&omega;');
        $chars['x'] = array('%', '><', '}{', '*', ')(');
        $chars['y'] = array('`/', '`(', '-/', '&psi;', '&lambda;', '&yen;');
        $chars['z'] = array('2', '~/_',  '%', '>_', '7_');
        return (is_string($specific_character) && in_array(strtolower($specific_character), array_keys($chars))) ? $chars[$specific_character] : $chars;
    }

    /**
     * Plugin Install
     *
     * @return void
     */
    public function install() {
        foreach(self::getDefaultAlphabet() as $char => $leet) add_option('ddleetspeak_'.$char, $leet);
    }

    /**
     * Plugin Uninstall
     *
     * @return void
     */
    public function uninstall() {
        foreach(self::getDefaultAlphabet() as $char => $leet) delete_option('ddleetspeak_'.$char);
    }

}