<?php namespace Picqer\Financials\Exact\Query;

trait Findable
{
    /**
     * Finds a model by its ID using filter
     *
     * @param string $id
     * @return Findable
     */
    public function find($id)
    {
        $result = $this->connection()->get($this->url, [
            '$filter' => $this->primaryKey . " eq guid'$id'"
        ]);

        return new self($this->connection(), $result);
    }

    /**
     * Finds a model by its ID using a key/value query string
     *
     * @param string $id
     * @return Findable
     */
    public function findById($id)
    {
        return $this->findWithParams([
            $this->primaryKey => $id
        ]);
    }

    /**
     * Finds models using a key/value query string
     *
     * @param array $params
     * @return Findable
     */
    public function findWithParams($params)
    {
        $params = collect($params)->map(function($value, $param) {
            $pattern = '/^\{?[a-zA-Z0-9]{8}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{12}\}?$/';
            if (preg_match($pattern, $value)) {
                $value = "guid'{$value}'";
            }
            return $param . '=' . urlencode($value);
        })->implode('&');

        $url = "{$this->url}?{$params}";
        $result = $this->connection()->get($url);
        unset($result[0]['__metadata']);

        return new self($this->connection(), $result[0]);
    }

    public function findWithSelect($id, $select = '')
    {
        //eg: $oAccounts->findWithSelect('5b7f4515-b7a0-4839-ac69-574968677d96', 'Code, Name');
        $result = $this->connection()->get($this->url, [
            '$filter' => $this->primaryKey . " eq guid'$id'",
            '$select' => $select
        ]);

        return new self($this->connection(), $result);
    }


    /**
     * Return the value of the primary key
     *
     * @param string $code the value to search for
     * @param string $key  the key being searched (defaults to 'Code')
     *
     * @return string (guid)
     */
    public function findId($code, $key='Code'){
        if ( $this->isFillable($key) ) {
            $format = $this->url == 'crm/Accounts' ? '%18s' : '%s';
            if (preg_match('/^[\w]{8}-([\w]{4}-){3}[\w]{12}$/', $code)) {
                $format = "guid'$format'";
            }
            elseif (is_string($code)) {
                $format = "'$format'";
            }

            $filter = sprintf("$key eq $format", $code);
            $request = array('$filter' => $filter, '$top' => 1, '$orderby' => $this->primaryKey);
            if( $records = $this->connection()->get($this->url, $request) ){
                return $records[0][$this->primaryKey];
            }
        }
    }


    public function filter($filter, $expand = '', $select = '', $system_query_options = null)
    {
        $originalDivision = $this->connection()->getDivision();

        if ($this->isFillable('Division') && preg_match("@Division[\t\r\n ]+eq[\t\r\n ]+([0-9]+)@i", $filter, $divisionId)) {
            $this->connection()->setDivision($divisionId[1]); // Fix division
        }

        $request = [
            '$filter' => $filter
        ];
        if (strlen($expand) > 0) {
            $request['$expand'] = $expand;
        }
        if (strlen($select) > 0) {
            $request['$select'] = $select;
        }
        if (is_array($system_query_options)) {
            // merge in other options
            // no verification of proper system query options
            $request = array_merge($system_query_options, $request);
        }

        $result = $this->connection()->get($this->url, $request);

        if (!empty($divisionId)) {
            $this->connection()->setDivision($originalDivision); // Restore division
        }

        return $this->collectionFromResult($result);
    }


    public function get()
    {
        $result = $this->connection()->get($this->url);

        return $this->collectionFromResult($result);
    }


    public function collectionFromResult($result)
    {
        // If we have one result which is not an assoc array, make it the first element of an array for the
        // collectionFromResult function so we always return a collection from filter
        if ((bool) count(array_filter(array_keys($result), 'is_string'))) {
            $result = [ $result ];
        }

        while ($this->connection()->nextUrl !== null)
        {
            $nextResult = $this->connection()->get($this->connection()->nextUrl);
            $result = array_merge($result, $nextResult);
        }
        $collection = [ ];
        foreach ($result as $r) {
            $collection[] = new self($this->connection(), $r);
        }

        return $collection;
    }
}
