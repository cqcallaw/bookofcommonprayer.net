<?php
namespace BCP;
use PDO;

class PsalmParser
{
	public	 	$passage	= NULL;
	public		$bookTitle	= NULL;
	public 		$version	= NULL;
	public		$error		= NULL;
	
	/**
	 * Create the Psalm Parser class.  Controller handles the difference between
	 * people who want the Psalms in their own version, and those who want it in the BCP,
	 * which is the default.  So, if this class is called, the version must be set.
	 * 
	 * @param string $this->version e.g. "NKJV", "RSV","SEV", etc.
	 */
	public function __construct($version = null)
	{
		$this->version = isset($this->version) ? $this->version : "NKJV";
		$this->bookTitle = null;
		$this->passage = null;
		$this->error = null;
	}
	
	/**
	 * Parse Psalm Method - Takes the passed psalm text and parses it for dislpay
	 * @param obj db The PDO Database object from QuickShell instantiation
	 * @param string psalm The coordinates of the Psalm; e.g. Psalm 119:15-22
	 */
	function parsePsalm($db,$psalm)
	{
		// SOMEONE'S TRYING TO ADD ITEMS TO THE URL
		$upperPsalm = strtoupper($psalm);
		if( (strstr($psalm,";")) || (strstr($psalm,"1=1")) || (strstr($upperPsalm,"FROM")) )
		{
			$this->error = "<h2>Error!</h2><strong>The Psalm is malformed!</strong><br>";
			return false;
		}
		$book_id = "19";//we know this is the id for psalms
		$is_canticle = stristr($psalm,"Canticle");
		if($is_canticle)
		{
			$this->passage .= parseCanticle($psalm);
			return;
		}
		$split = explode(":",$psalm);
		$chapter = str_replace("Psalm","",$split[0]);
		if($psalm == ":o:")
		{
			$this->bookTitle .= "<strong>:o: - Evening Psalms - :o:</strong><br>";
			$chapter = "NULL";
			$no_readings = 1;
		}
		if($psalm == "or")
		{
			$this->passage .= "<em> - or - </em><br>";
			$chapter = "NULL";
			$no_readings = 1;
		}
		if(isset($split[1]))
		{
			$verses = explode("-",$split[1]);
		}
		else
		{
			$verses = null;
		}
		if($verses[0] == null)
		{
			// I THINK THAT BRACKETS ARE ONLY FOR WHOLE PSALMS...
			$bracket = strstr($split[0],"[");
			$bracket2 = strstr($split[0],"]");
			if($bracket || $bracket2)
			{
				$psalm = str_replace("[","",$psalm);
				$psalm = str_replace("]","",$psalm);
				$chapter = trim($psalm);
			}
			if($chapter == 'NULL')
			{
				$this->passage .= "&nbsp;";
			}
			else
			{
				$query_psalm = $db->dbh->prepare(
						"SELECT verse, text 
						   FROM bible 
						  WHERE book_id= :book_id 
						    AND chapter= :chapter 
						    AND version= :version 
					   ORDER BY verse");
				$query_psalm->bindParam(':book_id',$book_id);
				$query_psalm->bindParam(':chapter',$chapter);
				$query_psalm->bindParam(':version',$this->version);
				$query_psalm->execute();
				$result = $query_psalm->fetchAll(PDO::FETCH_ASSOC);
				if($bracket || $bracket2)
				{
					$this->bookTitle .= "<p><b>[Psalm $psalm]</b><br>";
				}
				else
				{
					$this->bookTitle .= "<p><b>Psalm $psalm</b><br>";
				}
				foreach($result as $t)
				{
					if($t['verse'] == "0")
					{
						$t['verse'] = "<i class=copy>".$t['text']."</i>";
						$this->passage .= "<sup>$t[text]</sup><br>";
					}
					else
					{
						if($this->version == "NKJV")
						{
							$t['verse'] = str_replace("\n","<br>",$t['verse']);
						}
						$this->passage .= "<sup>$t[verse]</sup>$t[text]<br>";
					}
				}
			}
		}
		else
		{
			$paren = strstr($split[1],"(");
			$comma = strstr($split[1],",");
			if($comma)
			{
				// COMMA TYPE BELOW
				$sections = explode(",",$split[1]);
				////////////////////////////////////////////////
				// PART 1
				////////////////////////////////////////////////
				$part_1 = $sections[0];
				$verses = explode("-",$part_1);
				if($verses[1])//if there was a dash
				{
					$query_p1 = $db->dbh->prepare(
							"SELECT verse, text 
							   FROM bible 
							  WHERE version= :version 
							    AND book_id= :book_id 
							    AND chapter= :chapter 
							    AND verse BETWEEN :verses_0 AND :verses_1 
						   ORDER BY verse");
					$query_p1->bindParam(':book_id',$book_id);
					$query_p1->bindParam(':chapter',$chapter);
					$query_p1->bindParam(':version',$this->version);
					$query_p1->bindParam(':verses_0',$verses[0]);
					$query_p1->bindParam(':verses_1',$verses[1]);
					$query_p1->execute();
				}
				else // there's only one verse
				{
					$query_p1 = $db->dbh->prepare(
							"SELECT verse, text 
							   FROM bible 
							  WHERE version= :version 
							    AND book_id= :book_id 
							    AND chapter= :chapter 
							    AND verse= :part_1");
					$query_p1->bindParam(':book_id',$book_id);
					$query_p1->bindParam(':chapter',$chapter);
					$query_p1->bindParam(':version',$this->version);
					$query_p1->bindParam(':part_1',$part_1);
					$query_p1->execute();
				}
				////////////////////////////////////////////////
				// PART 2
				////////////////////////////////////////////////
				$part_2 = $sections[1];
				$verses2 = explode("-",$part_2);
				if($verses2[1])//if there was a dash
				{
					$query_p2 = $db->dbh->prepare(
							"SELECT verse, text 
							   FROM bible 
							  WHERE version= :version 
							    AND book_id= :book_id 
							    AND chapter= :chapter 
							    AND verse BETWEEN :verses_1 AND :verses_2 
						   ORDER BY verse");
					$query_p2->bindParam(':book_id',$book_id);
					$query_p2->bindParam(':chapter',$chapter);
					$query_p2->bindParam(':version',$this->version);
					$query_p2->bindParam(':verses_1',$verses2[0]);
					$query_p2->bindParam(':verses_2',$verses2[1]);
					$query_p2->execute();
				}
				else //only one verse
				{
					$query_p2 = $db->dbh->prepare(
							"SELECT verse, text 
							   FROM bible 
							  WHERE version= :version 
							    AND book_id= :book_id 
							    AND chapter= :chapter 
							    AND verse= :part_2");
					$query_p2->bindParam(':book_id',$book_id);
					$query_p2->bindParam(':chapter',$chapter);
					$query_p2->bindParam(':version',$this->version);
					$query_p2->bindParam(':part_2',$part_2);
					$query_p2->execute();
				}
				////////////////////////////////////////////////
				// PART 3
				////////////////////////////////////////////////
				if(isset($sections[2]))
				{
					$part_3 = $sections[2];
				}
				if(isset($part_3)) // in case of Amos 8:4-7(8-12) no part 3 would exist
				{
					$verses3 = explode("-",$part_3);
					$query_p3 = $db->dbh->prepare(
							"SELECT verse, text 
							   FROM bible 
							  WHERE version= :version 
							    AND book_id= :book_id 
							    AND chapter= :chapter 
							    AND verse BETWEEN :verses_1 AND :verses_2 
						   ORDER BY verse");
					$query_p2->bindParam(':book_id',$book_id);
					$query_p2->bindParam(':chapter',$chapter);
					$query_p2->bindParam(':version',$this->version);
					$query_p2->bindParam(':verses_1',$verses3[0]);
					$query_p2->bindParam(':verses_2',$verses3[1]);
					$query_p2->execute();
				}
				////////////////////////////////////////////////
				// SPIT OUT THE RESULTS
				////////////////////////////////////////////////
				if($part_1)
				{
					$result1 = $query_p1->fetchAll(PDO::FETCH_ASSOC);
				}
				$result2 = $query_p2->fetchAll(PDO::FETCH_ASSOC);
				if(isset($part_3))
				{
					$result3 = $query_p3->fetchAll(PDO::FETCH_ASSOC);
				}
				$this->bookTitle .= "<p><b>Psalm $psalm</b><br>";
				if($part_1)
				{
					foreach($result1 as $t)
					{
						if($this->version == "NKJV")
						{
							$t['text'] = str_replace("\n","<br>",$t['text']);
						}
						$this->passage .= "<sup>$t[verse]</sup>$t[text]<br>";
					}
				}
				foreach($result2 as $t)
				{
					if($this->version == "NKJV")
					{
						$t['text'] = str_replace("\n","<br>",$t['text']);
					}
					$this->passage .= "<sup>$t[verse]</sup>$t[text]<br>";
				}
				if(isset($part_3))
				{
					foreach($result3 as $t)
					{
						if($this->version == "NKJV")
						{
							$t[text] = str_replace("\n","<br>",$t[text]);
						}
						$this->passage .= "<sup>$t[verse]</sup>$t[text]<br>";
					}
				}
			}
			////////////////////////////////////////////////
			// PARENTHESIS TYPE BELOW
			////////////////////////////////////////////////
			elseif($paren)
			{
				$sections = explode("(",$split[1]);
				$part_1 = $sections[0];
				////////////////////////////////////////////////
				// PART 1
				////////////////////////////////////////////////
				if($part_1)//in the cases of Ezekiel 33:(1-6)7-11 no part 1 would exist
				{
					$verses = explode("-",$part_1);
					if($verses[1])//if there was a dash
					{
						$query_p1 = $db->dbh->prepare(
								"SELECT verse, text 
								   FROM bible 
								  WHERE version= :version 
								    AND book_id= :book_id 
								    AND chapter= :chapter 
								    AND verse BETWEEN :verses_1 AND :verses_2 
							   ORDER BY verse");
						$query_p1->bindParam(':book_id',$book_id);
						$query_p1->bindParam(':chapter',$chapter);
						$query_p1->bindParam(':version',$this->version);
						$query_p1->bindParam(':verses_1',$verses[0]);
						$query_p1->bindParam(':verses_2',$verses[1]);
						$query_p1->execute();
					}
					else // there's only one verse
					{
						$query_p1 = $db->dbh->prepare(
								"SELECT verse, text 
								   FROM bible 
								  WHERE version= :version 
								    AND book_id= :book_id 
								    AND chapter= :chapter 
								    AND verse= :part_1");
						$query_p1->bindParam(':book_id',$book_id);
						$query_p1->bindParam(':chapter',$chapter);
						$query_p1->bindParam(':version',$this->version);
						$query_p1->bindParam(':part_1',$part_1);
						$query_p1->execute();
					}
				}
				$sections1 = explode(")",$sections[1]);
				////////////////////////////////////////////////
				// PART 2
				////////////////////////////////////////////////
				$part_2 = $sections1[0];
				$verses2 = explode("-",$part_2);
				// FOR THE CASE OF PSALM 143:1-11(12) OR SIMILAR
				if(!$verses2[1])//there is no explosion
				{
					$query_p2 = $db->dbh->prepare(
							"SELECT verse, text 
							   FROM bible 
							  WHERE version= :version 
							    AND book_id= :book_id 
							    AND chapter= :chapter 
							    AND verse= :verse_1
						   ORDER BY verse");
					$query_p2->bindParam(':book_id',$book_id);
					$query_p2->bindParam(':chapter',$chapter);
					$query_p2->bindParam(':version',$this->version);
					$query_p2->bindParam(':verse_1',$verses2[0]);
					$query_p2->execute();
					$single_paren = 1;
				}
				else
				{
					$query_p2 = $db->dbh->prepare(
							"SELECT verse, text 
							   FROM bible 
							  WHERE version= :version 
							    AND book_id= :book_id 
							    AND chapter= :chapter 
							    AND verse BETWEEN :verse_1 AND :verse_2 
						   ORDER BY verse");
					$query_p2->bindParam(':book_id',$book_id);
					$query_p2->bindParam(':chapter',$chapter);
					$query_p2->bindParam(':version',$this->version);
					$query_p2->bindParam(':verse_1',$verses2[0]);
					$query_p2->bindParam(':verse_2',$verses2[1]);
					$query_p2->execute();
					$multi_paren = 1;
				}
				////////////////////////////////////////////////
				// PART 3
				////////////////////////////////////////////////
				$part_3 = $sections1[1];
				if($part_3) // in cases of Amos 8:4-7(8-12) no part 3 would exist
				{
					$verses3 = explode("-",$part_3);
					$query_p3 = $db->dbh->prepare(
							"SELECT verse, text 
							   FROM bible 
							  WHERE version= :version 
							    AND book_id= :book_id 
							    AND chapter= :chapter 
							    AND verse BETWEEN :verse_1 AND :verse_2 
						   ORDER BY verse");
					$query_p3->bindParam(':book_id',$book_id);
					$query_p3->bindParam(':chapter',$chapter);
					$query_p3->bindParam(':version',$this->version);
					$query_p3->bindParam(':verse_1',$verses3[0]);
					$query_p3->bindParam(':verse_2',$verses3[1]);
					$query_p3->execute();
				}
				////////////////////////////////////////////////
				// SPIT OUT THE RESULTS
				////////////////////////////////////////////////
				if($part_1)
				{
					$result1 = $query_p1->fetchAll(PDO::FETCH_ASSOC);
				}
				$result2 = $query_p2->fetchAll(PDO::FETCH_ASSOC);
				if($part_3)
				{
					$result3 = $query_p3->fetchAll(PDO::FETCH_ASSOC);
				}
				$this->bookTitle .= "<p><b>Psalm $psalm</b><br>";
				if($part_1)
				{
					foreach($result1 as $t)
					{
						if($this->version == "NKJV")
						{
							$t['text'] = str_replace("\n","<br>",$t['text']);
						}
						$this->passage .= "<sup>$t[verse]</sup>$t[text]<br>";
					}
				}
				$this->passage .= "<b>(</b>";
				foreach($result2 as $t)
				{
					if($this->version == "NKJV")
					{
						$t['text'] = str_replace("\n","<br>",$t['text']);
					}
					$this->passage .= "<sup>$t[verse]</sup>$t[text]";
					if( ($t['verse'] == $verses2[1]) && $multi_paren )
					{
						$this->passage .= "<b>)</b><BR>";
					}
					elseif( ($t['verse'] == $verses2[0]) && isset($single_paren ))
					{
						$this->passage .= "<b>)</b><BR>";
					}
					else
					{
						$this->passage .= "<br>";
					}
				}
				//echo "<b>)</b><br>";
				if($part_3)
				{
					foreach($result3 as $t)
					{
						if($this->version == "NKJV")
						{
							$t[text] = str_replace("\n","<br>",$t[text]);
						}
						$this->passage .= "<sup>$t[verse]</sup>$t[text]<br>";
					}
				}
			}
			else
			{
				if($chapter == 'NULL')
				{
					$this->passage .= "&nbsp;";
				}
				else
				{
					$query_psalm = $db->dbh->prepare(
							"SELECT verse, text 
							   FROM bible 
							  WHERE version= :version 
							    AND book_id= :book_id 
							    AND chapter= :chapter 
							    AND verse BETWEEN :verses_1 AND :verses_2  
						   ORDER BY verse");
					$query_psalm->bindParam(':version',$this->version);
					$query_psalm->bindParam(':book_id',$book_id);
					$query_psalm->bindParam(':chapter',$chapter);
					$query_psalm->bindParam(':verses_1',$verses[0]);
					$query_psalm->bindParam(':verses_2',$verses[1]);
					$query_psalm->execute();
					$result_psalm = $query_psalm->fetchAll(PDO::FETCH_ASSOC);
					$this->bookTitle .= "<p><b>Psalm $psalm</b><br>";
					foreach($result_psalm as $t)
					{
						if($this->version == "NKJV")
						{
							$t['text'] = str_replace("\n","<br>",$t['text']);
						}
						$this->passage .= "<sup>$t[verse]</sup>$t[text]<br>";
					}
				}
			}
		}
		if($this->version == "NKJV" && !isset($no_readings))
		{
			$this->passage .= " -- <a href='#copyright'>NKJV</a><br><br>";
		}
		return true;
	}
	/**
	 * TODO: Fix this to use PDO.  Happens as readings, right?  So need to fix this...
	 * @param unknown $psalm
	 */
	function parseCanticle($psalm)
	{
		$psalm = str_replace("Canticle","",$psalm);
		$psalm = trim($psalm);
		$query = $db->dbh->prepare("
				SELECT * 
				  FROM canticles 
				 WHERE cant_id = :psalm");
		$query->bindParam(":psalm",$psalm);
		$result = @mysql_query($query);
		while($data = @mysql_fetch_row($result))
		{
			$this->passage .= "<p><a name='$data[0]'></a><strong>$data[0] $data[1]</strong> <em>$data[2]</em><br>";
			$this->passage .= "$data[3]</p>";
			if($data[4])
			{
				$this->passage .= "<em>$data[4]</em><br>";
			}
			$this->passage .= "<pre>\n";
			$this->passage .= "$data[5]";
			$this->passage .= "</pre>";
		}
	}
}
?>
