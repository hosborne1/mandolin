#! /usr/bin/perl

#the idea is to to two things
#1 identify tables in a feature file, replacing them with a table link and putting the contents into associated files
#2 putting tables back into feature files by reading the feature file, looking for table links and putting the contents
$inTable = false;
$tablePrefix = "Table";
$count = 1;
$tableName = "";
$tableData = "";
$featureFile = "";
$fileSeparator = "";
#use time to create a unique id for folders and files
$epoc = time();

#check the operating system as set file separator accordingly
if($^O =~ /win.+|msys/){
    $fileSeparator = "\\";
}
else{
    $fileSeparator = "/";
}

#read the first argument to see if we are extracting tables or merging them - if neither then show some help
if(@ARGV[0] eq "-e"){
    #create a results directory and put the new feature file and any data files in it
    `mkdir $epoc`;
    #need to add ability to have paths
    $featureFile = @ARGV[1];
    #variable to hold new feature file output
    $featureFileOutput = "";
    #get the feature file
    open(FEATUREFILE, $featureFile) or die("Could not open file $featureFile");		# Open the file
    foreach $line (<FEATUREFILE>) {
        #print $line;
        #see if line matches the table pattern
        if($line =~ /^[^|]*\|(.+)\|[^|]*$/){
            #print "table pattern matched\n";
            #if not already in a table, must be a new table
            if($inTable eq false){
                $inTable = true;
                #print "in table $inTable\n";
                #create a new name for the table
                $tableName = $tablePrefix.$count;
                $count++;
                $tableData = "";
            }
            $tableData .= "$1\n";
        }
        else{
            #print "table pattern not matched\n";
            if($inTable eq true){
                $inTable = false;
                #print "in table $inTable\n";
                #write out the table
                writeTableData();
                $featureFileOutput .= $line;
            }
            else{
                #print "in table $inTable\n";
                $featureFileOutput .= $line;
            }
        }
    }
    close (FEATUREFILE);
    if($inTable eq true){writeTableData();}
    writeNewFeatureFile("$epoc$fileSeparator$featureFile");
}

elsif(@ARGV[0] eq "-m"){
    $featureFile = @ARGV[1];
    #get the feature file
    open(FEATUREFILE, $featureFile) or die("Could not open file $featureFile");		# Open the file
    foreach $line (<FEATUREFILE>) {
        if($line =~ /^<<(.+)>>$/){
            open(FEATUREFILE, $1) or die("Could not open file $featureFile");		# Open the file
            @table = <FEATUREFILE>;
            #open the data file
            foreach $row (@table){
                chomp($row);
                $featureFileOutput .= "|$row|\n";
            }
        }
        else{
            #print "in table $inTable\n";
            $featureFileOutput .= $line;
        }
    }
    writeNewFeatureFile("$featureFile.$epoc")
}
else{
 print "-e to extract\n-m to merge\n";   
}

sub writeTableData{
    open (TABLEFILE, ">>$epoc$fileSeparator$tableName") or die("Could not open file $epoc$fileSeparator$tableName"); 
    print TABLEFILE $tableData; 
    close (TABLEFILE);
    $featureFileOutput .= "<<".$tableName.">>\n";
    print "Created table name: $tableName\n";
}

sub writeNewFeatureFile{
    $filename = $_[0];
    open (NEWFEATUREFILE, ">>$filename") or die("Could not create file $filename"); 
    print NEWFEATUREFILE $featureFileOutput;
    close (NEWFEATUREFILE);
    print "Created feature file: $filename\n";
}