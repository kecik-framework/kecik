<?php
/**
 * Model
 * @package     Kecik
 * @author      Dony Wahyu Isp
 * @since       1.0.1-alpha
 **/
namespace Kecik;

if (!class_exists('Kecik\Model')) {

    class Model {
        protected $_field = array();
        protected $_where;
        protected $add = TRUE;
        protected $table = '';
        protected $fields = array();
        protected $values = array();
        protected $updateVar = array();

        /**
         * save
         * ID: Fungsi untuk menambah atau mengupdate record (Insert/Update)
         * EN: Function for adding/updating record (Insert/Update)
         * @return string SQL Query
         **/
        public function save() {
            $this->setFieldsValues();

            if ($this->table != '') {
                //** ID: Untuk menambah record | EN: For adding record
                if ($this->add == TRUE) {
                    $sql ="INSERT INTO $this->table ($this->fields) VALUES ($this->values)";
                //** ID: Untuk mengupdate record | EN: For updating record
                } else {
                    $sql ="UPDATE $this->table SET $this->updateVar $this->_where";
                }

                //** ID: silakan tambah code database sendiri disini
                //** EN: please add your database code in this

                //-- ID: Akhir tambah code database sendiri
                //-- EN: End of add your database code
            }

            return (isset($sql))?$sql:'';
        }

        /**
         * delete
         * ID: Fungsi untuk menghapus record
         * EN: Function for deleting record
         * @return string SQL Query
         **/
        public function delete() {
            $this->setFieldsValues();

            if ($this->table != '') {
                if ($this->_where != '') {
                    $sql = "DELETE FROM $this->table $this->_where";
                }

                //** ID: silakan tambah code database sendiri disini
                //** EN: please add your database code in this

                //-- ID: AKhir tambah code database sendiri
                //-- EN: End of add your database code
            }

            return (isset($sql))?$sql:'';
        }

        //** ID: Silakan tambah fungsi model sendiri disini
        //** EN: Please add your function/method of model in this

        //-- ID: Akhir tambah fungsi sendiri
        //-- EN: End of your function/method

        /**
         * Model Constructor
         * @param mixed $id
         **/
        public function __construct($id='') {
            $this->_where = '';
            if ($id != '') {
                if (is_array($id)) {
                    $and = array();
                    while(list($field, $value) = each($id)) {

                        if (preg_match('/<|>|!=/', $value))
                            $and[] = "$field$value";
                        else
                            $and[] = "$field='$value'";
                    }
                    $this->_where .= implode(' AND ', $and);
                } else {
                    $this->_where .= "id='".$id."'";
                }

                $this->add = FALSE;

                //** ID: Silakan tambah inisialisasi model sendiri disini
                //** EN: Please add your initialitation of model in this

                //-- EN: Akhir tambah inisialisasi model sendiri
                //-- EN: End of your initialitation model
            }
        }

        /**
         * setFieldValues
         * ID: Fungsi untuk menyetting Variable Fields dan Values
         * EN: Function/Method for setting fields and values variable
         **/
        private function setFieldsValues() {
            $fields = array_keys($this->_field);
            while(list($id, $field) = each($fields))
                $fields[$id] = "$fields[$id]";
            
            $this->fields = implode(',', $fields);

            $values = array_values($this->_field);
            $updateVar = array();
            while (list($id, $value) = each($values)){
                $values[$id] = "'$values[$id]'";
                $updateVar[] = "$fields[$id] = $values[$id]";
            }
            $this->values = implode(',', $values);
            $this->updateVar = implode(',', $updateVar);

            $this->_where = ($this->_where != '')?' WHERE '.$this->_where:'';
        }

        public function __set($var, $value) {
            $this->_field[$var] = addslashes($value);
        }

        public function __get($var) {
            return stripslashes($this->_field[$var]);
        }
    } 
}
