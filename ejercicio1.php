<?php
if (class_exists('SoapClient') && class_exists('SoapServer')) {
    echo "Las clases SoapClient y SoapServer existen en este servidor.";
} else {
    echo "Las clases SoapClient y SoapServer no existen en este servidor.";
}
?>
