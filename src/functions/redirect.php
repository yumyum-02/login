<?php

function redirect($redirectUrl) {
	header("Location: $redirectUrl");
	exit;
}