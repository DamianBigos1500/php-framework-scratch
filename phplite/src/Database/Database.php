<?php

namespace Phplite\Database;

use Exception;
use PDO;
use PDOException;
use Phplite\File\File;

class Database
{
  /**
   * Database instance
   */
  protected static $instance;

  /**
   * Database connection
   */
  protected static $connection;


  /**
   * Select data
   * 
   * @var array
   */
  protected static $select;

  /**
   * Table name
   * 
   * @var string
   */
  protected static $table;

  /**
   * Join data
   * 
   * @var string
   */
  protected static $join;


  /**
   * Where data
   * 
   * @var string
   */
  protected static $where;

  /**
   * Where binding
   * 
   * @var array
   */
  protected static $where_binding = [];

  /**
   * Group by data
   * 
   * @var string
   */
  protected static $group_by;

  /**
   * Having data
   * 
   * @var string
   */
  protected static $having;

  /**
   * Having binding
   * 
   * @var array
   */
  protected static $having_binding = [];


  /**
   * Order by data
   * 
   * @var string
   */
  protected static $order_by;


  /**
   * limit
   * 
   * @var string
   */
  protected static $limit;

  /**
   * offset
   * 
   * @var string
   */
  protected static $offset;

  /**
   * query
   * 
   * @var string
   */
  protected static $query;

  /**
   * all binding
   * 
   * @var string
   */
  protected static $binding = [];

  /**
   * Database constructor
   */
  public function __construct()
  {
  }

  /**
   * Connect to database
   */
  private static function connect()
  {
    if (!static::$connection) {
      $database_data = File::require_file("config/database.php");

      extract($database_data);
      $dns = "mysql:dbname" . $database . ";host=" . $host . "";
      $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_PERSISTENT => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "set NAMES " . $charset .  " COLLATE " . $collation
      ];

      try {
        static::$connection = new PDO($dns, $username, $password, $options);
      } catch (PDOException $e) {
        throw new Exception($e->getMessage());
      }
    }
  }

  /**
   * Get the instance of the class
   */
  public static function instance()
  {
    static::connect();
    if (!self::$instance) {
      self::$instance = new Database();
    }

    return self::$instance;
  }

  /**
   * query function
   * 
   * @param string $query
   * @return string
   */
  public static function query($query = null)
  {
    static::instance();
    if ($query == null) {

      if (!static::$table) {
        throw new Exception("Unknown table");
      }

      $query = "SELECT ";
      $query .= static::$select ?: "*";
      $query .= " FROM " . static::$table . " ";
      $query .= static::$join . " ";
      $query .= static::$where . " ";
      $query .= static::$group_by . " ";
      $query .= static::$having . " ";
      $query .= static::$order_by . " ";
      $query .= static::$limit . " ";
      $query .= static::$offset . " ";
    }

    static::$query = $query;
    static::$binding = array_merge(static::$where_binding, static::$having_binding);

    return static::instance();
  }

  /**
   * Select data from table
   * 
   * @return object $instance
   */
  public static function select()
  {
    $select = func_get_args();
    $select = implode(", ", $select);

    return static::instance();
  }

  /**
   * Define table
   * 
   * @param string $table
   * 
   * @return object $instance
   */
  public static function table($table)
  {
    static::$table = $table;

    return static::instance();
  }


  /**
   * Join table
   * 
   * @param string $table
   * @param string $first
   * @param string $operator
   * @param string $second
   * @param string $type
   * 
   * @return object $type
   */
  public static function join(
    $table,
    $first,
    $operator,
    $second,
    $type = "INNER"
  ) {
    static::$join .= " " . $type . " JOIN " . $table . " ON " . $first . $operator . $second . " ";

    return static::instance();
  }


  /**
   * Join table
   * 
   * @param string $table
   * @param string $first
   * @param string $operator
   * @param string $second
   * 
   * @return object $type
   */
  public static function rightJoin(
    $table,
    $first,
    $operator,
    $second
  ) {
    static::join(
      $table,
      $first,
      $operator,
      $second,
      "RIGHT"
    );

    return static::instance();
  }



  /**
   * Join table
   * 
   * @param string $table
   * @param string $first
   * @param string $operator
   * @param string $second
   * 
   * @return object $type
   */
  public static function leftJoin(
    $table,
    $first,
    $operator,
    $second
  ) {
    static::join(
      $table,
      $first,
      $operator,
      $second,
      "LEFT"
    );

    return static::instance();
  }

  /**
   * Where data
   * 
   * @param string $column
   * @param string $operator
   * @param string $value
   * @param string $type
   * 
   * @return string $type
   */
  public static function where($column, $operator, $value, $type = null)
  {
    $where = "`" . $column . '`' . $operator . " ? ";

    if (!static::$where) {
      $statement = " WHERE " . $where;
    } else {
      if ($type) {
        if ($type == null) {
          $statement = " AND " . $where;
        } else {
          $statement = " " . $type . " " . $where;
        }
      }

      static::$where .= $statement;
      static::$where_binding[] = htmlspecialchars($value);
    }

    return static::instance();
  }

  /**
   * Or Where 
   * 
   * @param string $column
   * @param string $operator
   * @param string $value
   * 
   * @return object $instance
   */
  public static function orWhere($column, $operator, $value)
  {
    static::where($column, $operator, $value, 'OR');

    return static::instance();
  }

  /**
   * Group By 
   * 
   * @return object $instance
   */
  public static function groupBy()
  {
    $group_by = func_get_args();
    $group_by = "GROUP BY " . implode(', ', $group_by) . " ";

    static::$group_by = $group_by;

    return static::instance();
  }

  /**
   * Having data 
   * 
   * @param string $column
   * @param string $operator
   * @param string $value
   * 
   * @return object $instance
   */
  public static function having($column, $operator, $value)
  {
    $having = "`" . $column . '`' . $operator . " ? ";

    if (!static::$where) {
      $statement = " HAVING " . $having;
    } else {
      $statement = " AND " . $having;
    }

    static::$having .= $statement;
    static::$having_binding[] = htmlspecialchars($value);

    return static::instance();
  }

  /**
   * Order by 
   * 
   * @param string $column
   * @param string $type
   * 
   * @return object $instance
   */
  public static function orderBy($column, $type = null)
  {
    $sep = static::$order_by ? " , " : " ORDER BY ";
    $type = strtoupper($type);
    $type = ($type != null && in_array($type, ["ASC", "DESC"])) ? $type : "ASC";
    $statement = $sep . $column  . " " . $type . " ";

    static::$order_by .= $statement;

    return static::instance();
  }

  /**
   * Limit 
   * 
   * @param string $limit
   * 
   * @return object $instance
   */
  public static function limit($limit)
  {
    static::$limit .= "LIMIT " . $limit . " ";

    return static::instance();
  }

  /**
   * Offset 
   * 
   * @param string $offset
   * 
   * @return object $instance
   */
  public static function offset($offset)
  {
    static::$offset .= "OFFSET " . $offset . " ";

    return static::instance();
  }

  /**
   * Get Query
   */
  public static function getQuery()
  {
    static::query();

    return static::$query;
  }
}
