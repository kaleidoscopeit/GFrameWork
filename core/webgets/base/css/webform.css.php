<?php
	session_start();	
	
	$_ = (object) '';
	$_->STATIC = &$_SESSION['__gidestatic__'];
	$_->CALL_UUID = array_shift(array_keys($_GET));
	$_->STYLE_REGISTRY_PREFIX = $_->STATIC['style_registry_prefix'];

	foreach((array) @$_->STATIC[$_->CALL_UUID]['style_registry'] as $key => $value){
		echo '.'.$_->STYLE_REGISTRY_PREFIX.$key.'{'.$value.'}';
	}

	unset($_->STATIC[$_->CALL_UUID]['style_registry']);
?>