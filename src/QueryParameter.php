<?php
namespace NYPL\Refinery;

use NYPL\Refinery\Exception\RefineryException;

/**
 * Query parameter objects used by Refinery Server.
 */
class QueryParameter
{
    /**
     * Comparison operator used for parameter value comparisons.
     *
     * @var string
     */
    private $operator = '';

    /**
     * Value of the query parameter.
     *
     * @var string
     */
    private $value = '';

    /**
     * Getter for the parameter comparison operator.
     *
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Setter for the parameter comparison operator.
     *
     * @param string $operator
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
    }

    /**
     * @param string $name
     *
     * @return bool|mixed
     */
    public function getValue($name = '')
    {
        if ($name) {
            if (is_array($this->value)) {
                if (array_key_exists($name, $this->value)) {
                    return $this->value[$name];
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return $this->value;
        }
    }

    /**
     * @param array $value
     *
     * @throws RefineryException
     */
    public function addValue(array $value = array())
    {
        if (!is_array($this->value)) {
            throw new RefineryException('Query parameter value can only be added to an array');
        } else {
            $this->value += $value;
        }
    }

    /**
     * Setter for the parameter value.
     *
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Convert the query parameter value to a string with the special comparison
     * operator. For example, if the comparison is not equal to value, i.e. "!=",
     * the parameter value will be converted to "not:value".
     *
     * @return null|string
     * @throws RefineryException
     */
    public function getValueWithOperator()
    {
        switch ($this->getOperator()) {
            case '!=':
                return 'not:' . $this->getValue();
                break;
            case null:
            case '=':
                return $this->getValue();
                break;
            default:
                throw new RefineryException('Filter operator specified (' . $this->getOperator() . ') does not match allowable operators,', 400);
                break;
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function updateValue($name = '', $value = null)
    {
        if ($value === null) {
            unset($this->value[$name]);
        } else {
            $this->value[$name] = $value;
        }
    }
}
