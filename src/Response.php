<?php


namespace PerfectMoney;


class Response
{
    /**
     * @var string
     */
    public $response;

    /**
     * @return array|mixed|string
     */
    public function toArray()
    {
        if(! empty($this->response) && $this->isJson())
            return json_decode($this->response, true);

        if(is_array($this->response))
            return $this->response;

    }

    /**
     * @return object|mixed|string
     */
    public function toObject()
    {
        if(! empty($this->response) && $this->isJson())
            return json_decode($this->response, false);

        if(is_object($this->response))
            return $this->response;
    }

    /**
     * @return string
     */
    public function get()
    {
        return $this->response;
    }

    /**
     * @return bool
     */
    public function isJson()
    {
        return @json_decode($this->response) && json_last_error() === 0;
    }

    /**
     * @param $name
     * @param array $arguments
     * @return bool
     */
    public function __call($name, $arguments = [])
    {
        if(strpos($name, 'get') === 0) {
            $property = lcfirst(substr($name, 3));
            if(!isset($this->toObject()->{$property}))
                return null;
            $object = $this->toObject()->{$property};
            if(is_object($object)){
                $instance = new Response();
                $instance->setResponse($object);
                return $this;
            }

            return $object;
        }
        if(strpos($name, 'has') === 0) {
            $property = lcfirst(substr($name, 3));
            return isset($this->toObject()->{$property});
        }
    }

    /**
     * @param string $response
     * @return Response
     */
    public function setResponse($message)
    {
        $this->response = $message;
        $this->response = $this->toObject();
        return $this;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if(!isset($this->toObject()->{$name}))
            return null;
        $response = $this->toObject()-> {$name};
        $this->setResponse($response);
        return $response;
    }


}
