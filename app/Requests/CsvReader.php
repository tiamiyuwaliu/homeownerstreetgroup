<?php
namespace App\Requests;

use Illuminate\Http\UploadedFile;

class CsvReader {

    protected $csv;

    /**
     * Method to read csv file
     * @param string $file
     * @return CsvReader
     */
    public function read($file) {
        $this->csv = array_map('str_getcsv', file($file));
        array_shift($this->csv);// remove the first line which is usally header
        return $this;
    }

    /**
     * Method validate if the file is a csv file
     * @param UploadedFile $file
     * @return boolean
     */
    public function isValid($file) {
        if($file->extension() != 'csv') return false;
        //add more level of security check to the file in case of bad or empty csv content
        if (!array_map('str_getcsv', file($file))) return false;
        return true;
    }

    /**
     * Method read the content of the csv file and return people with their title, first name, last name and initial
     * @return array
     */
    public function getPeople() {
        $people = [];
        foreach($this->csv as $row) {
            $row = $row[0];
            //replace & with and in the text in case it exist
            $row = str_replace('&', ' and ', $row);

            if (preg_match('#and#', $row)) {
                list($firstSegment, $secondSegment) = explode('and', $row);

                if (count(explode(" ", trim($firstSegment))) == 1) {
                    //row looks like Mrs and Mr John
                    $data = $this->formatString($secondSegment);
                    $data['title'] = $firstSegment;
                    $people[] = $data;
                    $people[] = $this->formatString($secondSegment);
                } else {
                    //row looks like Mrs Tom staff and Mr John doe
                    $people[] = $this->formatString($firstSegment);
                    //do for the second segments of the string
                    $people[] = $this->formatString($secondSegment);
                }
            } else {
                $people[] = $this->formatString($row);
            }

        }
        return $people;
    }

    /**
     * Helper function to format string into title, firstname, lastname and initial
     * @return array
     */
    private function formatString($string) {
        $splits = explode(" ", trim($string));
        if (count($splits) == 3) {
            list ($title, $firstName, $lastName) = $splits;
        } else {
            $firstName = 'null';
            list ($title,  $lastName) = $splits;
        }
        return  [
            'title' => $title,
            'first_name' => (substr( trim($firstName), -1, 1) === '.' or strlen(trim($firstName)) == 1) ? 'null' : $firstName,
            'initial' => (substr( trim($firstName), -1, 1) === '.'  or strlen(trim($firstName)) == 1) ? $firstName : 'null',
            'last_name' => $lastName
        ];
    }
}
