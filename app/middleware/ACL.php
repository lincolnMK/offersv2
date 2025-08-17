<?php
class ACL {
  public static function ensure(string $module, string $action): void { require_can($module, $action); }
}
