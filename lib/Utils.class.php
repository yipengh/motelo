<?php

class Utils {

    public static function yflash($key, $value) {
        if (isset($_SESSION['y.flash'])) {
            $_SESSION['y.flash'][$key] = $value;
        }
    }

    private static function removeComments($source) {
        if (!$source) {
            return '{}';
        }

        // replace block comments
        $source = preg_replace('/(\/\*((([^*]+)|\*(?!\/))*)\*\/)/', '', $source);

        // replace inline comments
        $source = preg_replace('/(\/\/.*[\n\r])/', '', $source);

        return $source;
    }

    public static function dat2json($source) {
        if (!$source) {
            return '{}';
        }

        // remove comments
        $source = self::removeComments($source);

        // strip white-spaces and line-ends
        $source = preg_replace('/[\s\n\r]+/', '', $source);

        // format to json key-value pairs
        $source = preg_replace('/([^=]+)=([^;]+);/', '"${1}":$2,', $source);

        // remove last comma
        if (substr($source, -1) == ',') {
            $source = substr($source, 0, strlen($source) - 1);
        }

        // parse arcs
        $source = preg_replace('/"arcs":{([<>0-9,]+)}/', '"arcs":[${1}]', $source);
        $source = preg_replace('/<([0-9]+,[0-9]+,[0-9.]+)>/', '[${1}]', $source);

        // encap to json object
        return '{' . $source . '}';
    }

    public static function sol2json($source) {
        if (!$source) {
            return '{}';
        }

        // remove comments
        $source = self::removeComments($source);

        // parse obj value and time
        $matches = array();
        preg_match('/z\s*=\s*(-?[0-9.]+)/', $source, $matches);
        $z = intval($matches[1]);
        preg_match('/time\s*=\s*([0-9.]+)/', $source, $matches);
        $t = floatval($matches[1]);
        
        return json_encode(array(
            'z'       => $z,
            't'       => $t,
            'f'       => self::parseSol($source, 'f', 4),
            'y'       => self::parseSol($source, 'y', 4),
            'x'       => self::parseSol($source, 'x'),
            'content' => $source
        ));
    }

    private static function parseSol($source, $key, $dimension = 2) {
        // construct RegEx pattern
        $pattern = '/' . $key;
        for ($i = 0; $i < $dimension; $i++) {
            $pattern .= '\[([0-9]+)\]';
        }
        $pattern .= '\s*=\s*(-?[0-9]+)/';

        // matches holder
        $matches = array();
        // result holder
        $result = array();

        // match
        preg_match_all($pattern, $source, $matches);

        // parse match result
        foreach ($matches[0] as $j => $value) {
            if ($matches[$dimension + 1][$j] != '0') {
                $tmp = array();
                for ($i = 0; $i <= $dimension; $i++) {
                    $tmp[] = intval($matches[$i + 1][$j]);
                }
                $result[] = $tmp;
            }
        }
        return $result;
    }

}

// END /lib/Utils.class.php