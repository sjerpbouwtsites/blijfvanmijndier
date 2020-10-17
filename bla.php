<?php

$ss = uniqid('', true);
echo preg_replace('/\W/', '-', $ss);
