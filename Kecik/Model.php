<?php
/**
 * Model
 * @package     Kecik
 * @author      Dony Wahyu Isp
 * @since       1.0.1-alpha
 **/
namespace Kecik;

if (!class_exists('Kecik\Model')) {
    /**
     * Class Model
     * @package Kecik
     */
    class Model
    {
        protected $field = array();
        protected $where;

        protected $add = TRUE;
        protected $table = '';
        protected $fields = array();
        protected $values = array();
        protected $updateVar = array();

        /**
         * @return string
         */
        public function save()
        {
            $this->setFieldsValues();

            if ($this->table != '') {

                //** ID: Untuk menambah record | EN: For adding record
                if ($this->add == TRUE) {
                    $sql = "INSERT INTO $this->table ($this->fields) VALUES ($this->values)";
                    //** ID: Untuk mengupdate record | EN: For updating record
                } else {
                    $sql = "UPDATE $this->table SET $this->updateVar $this->where";
                }

                //** ID: silakan tambah code database sendiri disini
                //** EN: please add your database code in this

                //-- ID: Akhir tambah code database sendiri
                //-- EN: End of add your database code
            }

            return (isset($sql)) ? $sql : '';
        }

        /**
         * @return string
         */
        public function delete()
        {
            $this->setFieldsValues();

            if ($this->table != '') {

                if ($this->where != '') {
                    $sql = "DELETE FROM $this->table $this->where";
                }

                //** ID: silakan tambah code database sendiri disini
                //** EN: please add your database code in this

                //-- ID: AKhir tambah code database sendiri
                //-- EN: End of add your database code
            }

            return (isset($sql)) ? $sql : '';
        }

        //** ID: Silakan tambah fungsi model sendiri disini
        //** EN: Please add your function/method of model in this

        //-- ID: Akhir tambah fungsi sendiri
        //-- EN: End of your function/method

        /**
         * Model constructor.
         * @param string $id
         */
        public function __construct($id = '')
        {
            $this->where = '';

            if ($id != '') {

                if (is_array($id)) {
                    $and = array();

                    while (list($field, $value) = each($id)) {

                        if (preg_match('/<|>|!=/', $value)) {
                            $and[] = "$field$value";
                        } else {
                            $and[] = "$field='$value'";
                        }

                    }

                    $this->where .= implode(' AND ', $and);
                } else {
                    $this->where .= "id='" . $id . "'";
                }

                $this->add = FALSE;

                //** ID: Silakan tambah inisialisasi model sendiri disini
                //** EN: Please add your initialitation of model in this

                //-- EN: Akhir tambah inisialisasi model sendiri
                //-- EN: End of your initialitation model
            }
        }

        /**
         *
         */
        private function setFieldsValues()
        {
            $fields = array_keys($this->field);

            while (list($id, $field) = each($fields)) {
                $fields[$id] = "$field";
            }

            $this->fields = implode(',', $fields);

            $values = array_values($this->field);
            $updateVar = array();

            while (list($id, $value) = each($values)) {
                $values[$id] = "'$value'";
                $updateVar[] = "$fields[$id] = $values[$id]";
            }

            $this->values = implode(',', $values);
            $this->updateVar = implode(',', $updateVar);

            $this->where = ($this->where != '') ? ' WHERE ' . $this->where : '';
        }

        /**
         * @param $var
         * @param $value
         */
        public function __set($var, $value)
        {
            $this->field[$var] = addslashes($value);
        }

        /**
         * @param $var
         * @return string
         */
        public function __get($var)
        {
            return stripslashes($this->field[$var]);
        }
    }
}
