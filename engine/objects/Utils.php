<?php
function Utils(string $act) {
  switch ($act) {
    case 'time':
      return time();
  }
}