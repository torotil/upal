<?php

namespace Upal;

class CompatTest extends DrupalUnitTestCase {

  /**
   * Test that this test case is actually a \Upal\DrupalUnitTestCase.
   */
  public function testClassAlias() {
    $this->assertInstanceOf('\\DrupalUnitTestCase', $this);
  }

}
