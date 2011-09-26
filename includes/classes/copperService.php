<?php

class copperService {

    /**
     * __construct
     *
     * The constructor camelize the method
     * @param string $method
     * @param array $params
     */
    public function __construct($method, $params) {
        if (isset($method) && isset($params)) {
            try {
                $method = copperStr::camelize($method);
                $this->$method($params);
            } catch (Exception $exc) {
                return $this->doServiceError($exc->getMessage());
            }
        }else{
            return $this->doServiceError('Error in method and params');
        }
    }

    /**
     * doAjaxError
     *
     * Response an ajax error
     *
     * @param string $msg
     * @return false
     */
    protected function doServiceError($msg) {
        $message = array('error' => true, "msg" => $msg);
        echo json_encode($message);
        copperConfig::doError($msg);
        return false;
    }

}

