<?php

class DocDown {

	public $indentifiers;

	public function __construct($input)
	{
		$read = $this->readInput($input);
		$this->singleIdentifiers = $this->setSingleSyntaxIdentifiers();
		$this->wrappedIdentifiers = $this->setWrappedSyntaxIdentifiers();

		$newLines = $this->setElement($this->findSyntax($read), $read);
		$this->printNewLines($newLines);
		
	}

	public function readInput($input)
	{
		return file($input);
	}

	public function setSingleSyntaxIdentifiers()
	{
		$identifiers = ['#', '>'];
		return $identifiers;
	}

	public function setWrappedSyntaxIdentifiers()
	{
		$identifiers = ['_'];
		return $identifiers;
	}
	
	public function findSyntax($fileArr)
	{
		foreach ($fileArr as $line) {
			$html[] = $this->searchLine($line);
		}
		return $html;
	}

	public function searchLine($line)
	{
		$identifiers = array_merge($this->singleIdentifiers, $this->wrappedIdentifiers);
		foreach ($identifiers as $identifier) {
			if (strpos($line, $identifier) !== false) {
				if (substr_count($line, $identifier) == 1) {
					return $this->getSingleIdentifierPurpose($identifier);
				} elseif (substr_count($line, $identifier) == 2) {
					if ($this->checkIfWrapped($line, $identifier)) {
						return $this->getWrappedIndentifierPurpose($identifier);
					}
				}
			}
		}
	}

	public function checkIfWrapped($line, $identifier)
	{
		$identifiers = array($identifier, $identifier);

		foreach ($identifiers as $identifier) {
			$positions[] = strpos($line, $identifier);
			$line = substr($line, strpos($line, $identifier) + 1, strlen($line));
		}

		if (($positions[1] - $positions[0]) > 1) {
			return true;
		} else {
			return false;
		}
	}

	public function getSingleIdentifierPurpose($identifier)
	{
		switch ($identifier) {
			case '#':
				$html = '<h1>';
				break;
		}
		return $html;
	}

	public function getWrappedIndentifierPurpose($identifier) {
		switch ($identifier) {
			case '_':
				$html = '<i>';
				break;
		}

		return $html;
	}


	public function setElement($html, $read)
	{
		for ($i = 0; $i <= count($html); $i++) {
			switch ($html[$i]) {
				case '<h1>':
					$newLine[] = '<h1>'.str_replace($this->matchIdentifier($html[$i]), '', $read[$i]).'</h1>';
					break;
				case '<i>':
					$newLine[] = '<i>'.str_replace($this->matchIdentifier($html[$i]), '', $read[$i]).'</i>';
					break;
			}
		}

		return $newLine;
	}

	public function matchIdentifier($htmlTag)
	{
		$html_identifiers = ['<h1>' => '#', '<i>' => '_'];

		return $html_identifiers[$htmlTag];

	}

	public function printNewLines($newLines)
	{
		foreach ($newLines as $newLine) {
			echo $newLine . '<br>';
		}
	}

}