<?php
    abstract class SanitizeCustom {

        public static function DNI($string){
            // Elimina cualquier carácter que no sea un número
            $string = preg_replace("/\D/", "", $string);

            // Verifica que tenga exactamente 8 caracteres
            if(strlen($string) !== 8)
                return FALSE;

            return $string;
        }

        public static function CUIL($string){
            // Elimina cualquier carácter que no sea un número
            $string = preg_replace("/\D/", "", $string);

            // Verifica que tenga exactamente 8 caracteres
            if(strlen($string) !== 11)
                return FALSE;

            return $string;
        }

    }//-- end CLASS
