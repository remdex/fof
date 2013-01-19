<?php

require_once 'PHPUnit/Framework.php';

class erLhtestRunner
{
	private $tests;
    public function __construct($args)
    {        
    	$this->tests = array();
		if ($args[0] != 'all') {		
			foreach ($args as $test) {			    
				if (file_exists("lib/core/lh".$test."/tests/suite.php"))
					$this->tests[] = $test;
			}
		} else {	
			$dirlist = scandir("lib/core");
			foreach ($dirlist as $dir) {
				if ($dir != "." and $dir != "..") {
					if (file_exists("lib/core/".$dir."/tests/suite.php")) {
						$this->tests[] = substr($dir,2);
					}
				}
			}
		}
    }
    
    public function run() {
		$suite = new PHPUnit_Framework_TestSuite;
		foreach ($this->tests as $test) {
			include("lib/core/lh".$test."/tests/suite.php");
			$suite->addTestSuite('lh_'.$test.'_test');
		}
		$result = new PHPUnit_Framework_TestResult;
		$result->addListener(new TestListener);
		$suite->run($result);
		echo "---------------\n";

		if (!$result->wasSuccessful()) {
			echo "\n";
			if (count($result->errors()) > 0) {
				$nr = 1;
				echo "ERRORS:\n----------------\n";
				foreach($result->errors() as $error) {
					echo $nr++.") ";
					print_r($error->exceptionMessage());
					echo "\n";
				}
				echo "----------------\n";
			}
			if (count($result->failures()) > 0) {
				$nr = 1;
				echo "\nFAILURES:\n----------------\n";
				foreach($result->failures() as $failure) {
					echo $nr++.") ";
					print_r($failure->exceptionMessage());
					echo "\n";
				}
				echo "----------------\n";
			}
		}	
		echo "Success: ",($result->count()-$result->failureCount()-$result->errorCount()),"\n";
		echo "Failed: ",$result->failureCount(),"\n";
		echo "Errors: ",$result->errorCount(),"\n";
		echo "Total: ",$result->count(),"\n";
    }
}
 
class TestListener
implements PHPUnit_Framework_TestListener
{
	private $state;
  public function
  addError(PHPUnit_Framework_Test $test,
           Exception $e,
           $time)
  {
  	$this->state = "Error";
  }
  public function
  addFailure(PHPUnit_Framework_Test $test,
             PHPUnit_Framework_AssertionFailedError $e,
             $time)
  {
  	$this->state = "Fail";
  }
 
  public function
  addIncompleteTest(PHPUnit_Framework_Test $test,
                    Exception $e,
                    $time)
  {
  	$this->state = "Incomplete";
  }
 
  public function
  addSkippedTest(PHPUnit_Framework_Test $test,
                 Exception $e,
                 $time)
  {
  	$this->state = "Skipped";
  }
 
  public function startTest(PHPUnit_Framework_Test $test)
  {
  	$this->state = "Success";
  }
 
  public function endTest(PHPUnit_Framework_Test $test, $time)
  {
    printf(
      "Test '%s': %s\n",
      substr($test->getName(),4), $this->state
    );
  }
 
  public function
  startTestSuite(PHPUnit_Framework_TestSuite $suite)
  {
  	if (strlen($suite->getName()) > 0)
  	echo "--- ",$suite->getName()," ---\n";
  }
 
  public function
  endTestSuite(PHPUnit_Framework_TestSuite $suite)
  {

  }
}