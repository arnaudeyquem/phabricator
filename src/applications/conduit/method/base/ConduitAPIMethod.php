<?php

/*
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

abstract class ConduitAPIMethod {

  abstract public function getMethodDescription();
  abstract public function defineParamTypes();
  abstract public function defineReturnType();
  abstract public function defineErrorTypes();
  abstract protected function execute(ConduitAPIRequest $request);

  public function __construct() {

  }

  public function getErrorDescription($error_code) {
    return idx($this->defineErrorTypes(), $error_code, 'Unknown Error');
  }

  public function executeMethod(ConduitAPIRequest $request) {
    return $this->execute($request);
  }

  public function getAPIMethodName() {
    return self::getAPIMethodNameFromClassName(get_class($this));
  }

  public static function getClassNameFromAPIMethodName($method_name) {
    $method_fragment = str_replace('.', '_', $method_name);
    return 'ConduitAPI_'.$method_fragment.'_Method';
  }

  public function shouldRequireAuthentication() {
    return true;
  }

  public static function getAPIMethodNameFromClassName($class_name) {
    $match = null;
    $is_valid = preg_match(
      '/^ConduitAPI_(.*)_Method$/',
      $class_name,
      $match);
    if (!$is_valid) {
      throw new Exception(
        "Parameter '{$class_name}' is not a valid Conduit API method class.");
    }
    $method_fragment = $match[1];
    return str_replace('_', '.', $method_fragment);
  }

}
