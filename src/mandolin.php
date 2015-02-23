<?php

//the idea is to to two things
//1 identify tables in a feature file, replacing them with a table link and putting the contents into associated files
//2 putting tables back into feature files by reading the feature file, looking for table links and putting the contents
$inTable = false;
$tablePrefix = "table_";
$count = 1;
$tableName = "";
$tableData = "";
$featureFile = "";
$directorySeparator = DIRECTORY_SEPARATOR;
#use time to create a unique id for folders and files
$epoc = $today = date("YmdHis"); ;
$tableDir = "tables";
$featureFileOutput = "";

#read the first argument to see if we are extracting tables or merging them - if neither then show some help
if($argv[1] == "-e"){
    #create a results directory and put the new feature file and any data files in it
	$output = shell_exec('mkdir '.$epoc.DIRECTORY_SEPARATOR.$tableDir);
    #need to add ability to have paths
    $featureFile = $argv[2];
    #variable to hold new feature file output
    $featureFileOutput = "";
    #get the feature file
	$featureFileStream = fopen($featureFile, "r") or die("Unable to open feature file!");
	while(!feof($featureFileStream)) {
		$line = fgets($featureFileStream);
        #print $line;
        #see if line matches the table pattern
		$tablePattern = '/^[^|]*\|(.+)\|[^|]*$/';
        if(preg_match($tablePattern, $line, $matches)){
            #print "table pattern matched\n";
            #if not already in a table, must be a new table
            if($inTable == false){
                $inTable = true;
                #print "in table $inTable\n";
                #create a new name for the table
                $tableName = $tablePrefix.$count;
                $count++;
                $tableData = "";
            }
            $tableData .= $matches[1]."\n";
        }
        else{
            #print "table pattern not matched\n";
            if($inTable == true){
                $inTable = false;
                #print "in table $inTable\n";
                #write out the table
                $featureFileOutput .= writeTableData($epoc,$tableDir,$tableName,$featureFileOutput,$tableData);
                $featureFileOutput .= $line;
            }
            else{
                #print "in table $inTable\n";
                $featureFileOutput .= $line;
            }
        }		
	}
	fclose($featureFileStream);

    if($inTable == true){
		$featureFileOutput .= writeTableData($epoc,$tableDir,$tableName,$featureFileOutput,$tableData);
	}
    writeNewFeatureFile($epoc.DIRECTORY_SEPARATOR.$featureFile, $featureFileOutput);
}

elseif($argv[1] == "-m"){
    $featureFile = $argv[2];
	$tableDirectory = $argv[3];
	$newFeatureFile = $argv[4];
    #get the feature file
	$featureFileStream = fopen($featureFile, "r") or die("Unable to open feature file!");
	while(!feof($featureFileStream)) {
		$line = fgets($featureFileStream);
		$tablePlaceholderPattern = '/^<<(.+)>>$/';
        if(preg_match($tablePlaceholderPattern, $line, $matches)){
			$tableFile = fopen($tableDirectory.DIRECTORY_SEPARATOR.$matches[1], "r") or die("Could not open file ".$tableDirectory.DIRECTORY_SEPARATOR.$matches[1]);
			while(!feof($tableFile)) {
				$row = fgets($tableFile);
				$row = str_replace("\n", '', $row);
                $featureFileOutput .= "|".$row."|\n";
            }
			fclose($tableFile);
        }
        else{
            #print "in table $inTable\n";
            $featureFileOutput .= $line;
        }
    }
    writeNewFeatureFile($newFeatureFile,$featureFileOutput);
}
else{
 print "-e to extract\nExample: mandolin.php -e name_of_feature_file\n-m to merge\nExample: mandolin.php -m name_of_extracted_feature_file location_of_tables name_of_feature_file_to_be_made\n";   
}

function writeTableData($epoc,$tableDir,$tableName,$featureFileOutput,$tableData){
	$tableFile = fopen($epoc.DIRECTORY_SEPARATOR.$tableDir.DIRECTORY_SEPARATOR.$tableName, "w") or die("Unable to open table file!");
	$tableData = rtrim($tableData, "\n")
	fputs($tableFile, $tableData);
	fclose($tableFile);
	return "<<".$tableName.">>\n";
    print "Created table name: ".$tableName."\n";
}

function writeNewFeatureFile($newFeatureFile,$featureFileOutput){
	$fFile = fopen($newFeatureFile, "w") or die("Unable to open file!");
	fputs($fFile, $featureFileOutput);
	fclose($fFile);
    print "Created feature file: ".$newFeatureFile."\n";
}

?>