<?php
var_dump(preg_replace('/(.*)\.\d+$/', '$1.x-dev', '3.0.55'));