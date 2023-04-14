<?php

namespace Otto;
use PDO;

class Challenge
{
    protected $pdoBuilder;

    public function __construct()
    {
        $config = require __DIR__ . '/../config/database.config.php';
        $this->setPdoBuilder(new PdoBuilder($config));
    }

    /**
     * Use the PDOBuilder to retrieve all the director records
     *
     * @return array
     */
    public function getDirectorRecords()
    {
        $qry = 'SELECT * FROM directors';
        return $this->getPdoBuilder()->getPdo()->query($qry)->fetchAll();
    }

    /**
     * Use the PDOBuilder to retrieve a single director record with a given id
     *
     * @param int $id
     * @return array
     */
    public function getSingleDirectorRecord($id)
    {
        $qry = 'SELECT * FROM directors WHERE id = :id';
        
        $stmt = $this->getPdoBuilder()->getPdo()->prepare($qry);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Use the PDOBuilder to retrieve all the business records
     *
     * @return array
     */
    public function getBusinessRecords()
    {
        $qry = 'SELECT * FROM businesses';
        return $this->getPdoBuilder()->getPdo()->query($qry)->fetchAll();
    }

    /**
     * Use the PDOBuilder to retrieve a single business record with a given id
     *
     * @param int $id
     * @return array
     */
    public function getSingleBusinessRecord($id)
    {
        $qry = 'SELECT * FROM businesses WHERE id = :id';
        
        $stmt = $this->getPdoBuilder()->getPdo()->prepare($qry);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
        
    }

    /**
     * Use the PDOBuilder to retrieve a list of all businesses registered on a particular year
     *
     * @param int $year
     * @return array
     */
    public function getBusinessesRegisteredInYear($year) {
        $qry = 'SELECT * FROM businesses WHERE year(registration_date) = :year';

        $stmt = $this->getPdoBuilder()->getPdo()->prepare($qry);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Use the PDOBuilder to retrieve the last 100 records in the directors table
     *
     * @return array
     */
    public function getLast100Records()
    {
        $qry = 'SELECT * FROM directors ORDER BY id DESC LIMIT 100';
        return $this->getPdoBuilder()->getPdo()->query($qry)->fetchAll();
    }

    /**
     * Use the PDOBuilder to retrieve a list of all business names with the director's name in a separate column.
     * The links between directors and businesses are located inside the director_businesses table.
     *
     * Your result schema should look like this;
     *
     * | business_name | director_name |
     * ---------------------------------
     * | some_company  | some_director |
     *
     * @return array
     */
    public function getBusinessNameWithDirectorFullName()
    {
        $qry = <<<'EOT'
SELECT 
        b.name as business_name, 
        concat(d.first_name, " ", d.last_name) as director_name
    FROM director_businesses as dbiz 
    JOIN businesses as b on b.id = dbiz.business_id 
    JOIN directors as d on d.id = dbiz.director_id;
EOT;
        return $this->getPdoBuilder()->getPdo()->query($qry)->fetchAll();
    }

    /**
     * 
     * Use the PDOBuilder to populate the table in index.html
     * 
     * It wasn't in the original test, but this fixes the call to a missing method getRecords() in index.php
     *   
     * @return array
     */
    public function getRecords()
    {

        $qry = <<<'EOT'
SELECT 
        b.id, 
        d.first_name,
        d.last_name, 
        b.name, 
        b.registered_address, 
        b.registration_number
    FROM director_businesses as dbiz
    JOIN businesses as b on b.id = dbiz.business_id
    JOIN directors as d on d.id = dbiz.director_id;
EOT;
        return $this->getPdoBuilder()->getPdo()->query($qry)->fetchAll();
    }

    /**
     * @param PdoBuilder $pdoBuilder
     * @return $this
     */
    public function setPdoBuilder(PdoBuilder $pdoBuilder)
    {
        $this->pdoBuilder = $pdoBuilder;
        return $this;
    }

    /**
     * @return PdoBuilder
     */
    public function getPdoBuilder()
    {
        return $this->pdoBuilder;
    }
}