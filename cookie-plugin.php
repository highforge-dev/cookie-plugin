<?php


add_filter("gform_field_value_hfref", "populate_hfref");
function populate_hfref() {
	return $_COOKIE["hfref"];
}
