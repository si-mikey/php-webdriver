<?php
// Copyright 2004-present Facebook. All Rights Reserved.
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//     http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

require_once('WebDriverBase.php');
require_once('WebDriverSession.php');

class PHPWebDriver_WebDriver extends PHPWebDriver_WebDriverBase {

  function __construct($executor = "") {
    parent::__construct($executor);
  }

  protected function methods() {
    return array(
      'status' => 'GET',
    );
  }

  public function session($browser = 'firefox',
                          $additional_capabilities = array()) {
    $desired_capabilities = array_merge(
      $additional_capabilities,
      array('browserName' => $browser));

    $results = $this->curl(
      'POST',
      '/session',
      array('desiredCapabilities' => $desired_capabilities),
      array(CURLOPT_FOLLOWLOCATION => true));

    return new PHPWebDriver_WebDriverSession($results['info']['url']);
  }
  
  public function sessions() {
    $result = $this->curl('GET', '/sessions');
    $sessions = array();
    foreach ($result['value'] as $session) {
      $sessions[] = new PHPWebDriver_WebDriverSession(
        $this->url . '/session/' . $session['id']);
    }
    return $sessions;
  }
}
