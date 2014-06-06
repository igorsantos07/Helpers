<?php
namespace Helper;

/**
 * Utility class for arrays. Called Arrays because Array is a reserved word :P
 * Originally based on https://github.com/igorsantos07/Yii-Extensions/blob/master/helpers/ArrayHelper.php
 *
 * @see https://github.com/igorsantos07/Yii-Extensions/blob/master/helpers/ArrayHelper.php
 * @author Igor Santos <igorsantos07@gmail.com>
 */
abstract class Arrays {

	/**
	 * Variable used by {@link compare} method. It's the key that's going to be used for comparisons.
	 * @var string
	 * @static
	 */
	public static $comparison_key = '';

	const REMOVE_WHITELIST = 'w';
	const REMOVE_BLACKLIST = 'b';

	/**
	 * Erases all falsy values from the $dirty_array and, optionally, reindexes it, then returns.
	 * The original implementation relied on a handmade implementation of array_filter. This one just uses it :P
	 * @param array $dirty_array
	 * @param boolean $reindex [optional] If it's needed to reindex the keys; defaults to true.
	 * @param callable $whats_empty [optional] Returns true on what is falsy.
	 * @see array_filter()
	 * @return array
	 */
	public static function clear(array $dirty_array, $reindex = true, callable $whats_empty = null) {
		$array = array_filter($dirty_array, $whats_empty);
		if ($reindex) $array = array_merge($array);
		return $array;
	}

	/**
	 * Erases all the keys from $crowded_array, except the ones that are in the $whitelist, and returns the final array.
	 * @param array $crowded_array
	 * @param mixed $whitelist a string with one key, or an array with many
	 * @return array
	 * @see blacklist
	 */
	public static function whitelist(array $crowded_array, $whitelist) {
		return self::remove($crowded_array, $whitelist, self::REMOVE_WHITELIST);
	}

	/**
	 * Erases all the keys from $crowded_array that are in the $blacklist, and returns the final array.
	 * @param array $messy_array
	 * @param mixed $blacklist a string with one key, or an array with many
	 * @return array
	 * @see whitelist
	 */
	public static function blacklist(array $messy_array, $blacklist) {
		return self::remove($messy_array, $blacklist, self::REMOVE_BLACKLIST);
	}

	/**
	 * Generic method to envelope black and whitelist functionalities. To further documentation about theses, see {@link whitelist} and {@link blacklist}.
	 * @param array $array the array being filtered
	 * @param mixed $list a string (or an array of strings) of keys to be removed/kept
	 * @param string $type one of {@link REMOVE_WHITELIST} or {@link REMOVE_BLACKLIST}
	 * @return array
	 */
	private static function remove(array $array, $list, $type) {
		if (!is_array($list)) $list = [$list];

		$remove = [];
		switch ($type) {
			case self::REMOVE_BLACKLIST:
				foreach ($list as $blacklisted)
					if (array_key_exists($blacklisted, $array)) $remove[] = $blacklisted;
				break;
			case self::REMOVE_WHITELIST:
				foreach ($array as $prop => $value)
					if (!in_array($prop, $list)) $remove[] = $prop;
				break;
		}

		foreach ($remove as $prop)
			unset($array[$prop]);

		return $array;
	}

	/**
	 * Method to make the use of {@link usort} e {@link uasort} a little easier with arrays of arrays.
	 *
	 * Those functions receive as the second argument a custom ordering function. You should place the
	 * key whose values will be used as order-key in {@link $compare_key} and give this method to usort().
	 *
	 * Example:
	 * <code>
	 * //ordering an array of arrays by the 'name' key
	 * $messy = array(
	 * array('id' => 2, 'name' => 'Zebra'),
	 * array('id' => 3, 'name' => 'Dog'),
	 * array('id' => 4, 'name' => 'Elephant')
	 * );
	 * ArrayHelper::$compare_key = 'nome';
	 * usort($messy, 'ArrayHelper::compare');
	 * </code>
	 *
	 * @param array $a
	 * @param array $b
	 * @throws \Exception
	 * @return integer
	 */
	public static function compare(array $a, array $b) {
		if (empty(self::$comparison_key)) throw new \Exception('ArrayHelper::$comparison_key should not be empty');

		if ($a[self::$comparison_key] == $b[self::$comparison_key])
			return 0;
		else
			return ($a[self::$comparison_key] < $b[self::$comparison_key]) ? -1 : 1;
	}

	/**
	 * Unsets values from the array using the value, instead of the key.
	 * If the given $value appears in the $array more than one time, all of them will be erased.
	 * @param array $array the array that should be searched
	 * @param mixed $value the value that's going to be erased
	 * @param boolean $strict [optional] if the search should be type-strict. Defaults to false.
	 * @return null
	 */
	public static function unsetByValue(array &$array, $value, $strict = false) {
		$keys = array_keys($array, $value, $strict);
		if (is_array($keys))
			foreach ($keys as $key) unset($array[$key]);
	}
}