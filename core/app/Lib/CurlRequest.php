<?php

namespace App\Lib;

class CurlRequest
{

	/**
	 * GET request using curl
	 *
	 * @return mixed
	 */
	public static function curlContent($url, $header = null)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		if ($header) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

	/**
	 * POST request using curl
	 *
	 * @return mixed
	 */
	public static function curlPostContent($url, $postData = null, $header = null)
	{
		if (is_array($postData)) {
			$params = http_build_query($postData);
		} else {
			$params = $postData;
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		if ($header) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

	/**
	 * DELETE request using curl
	 *
	 * @return mixed
	 */
	public static function curlDeleteContent($url, $data = null, $header = null)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		if ($header) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

		if ($data) {
			$params = is_array($data) ? http_build_query($data) : $data;
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		}

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

	/**
	 * File upload using curl (multipart/form-data)
	 *
	 * @return mixed
	 */
	public static function curlFileUpload($url, $postData = [], $fileField = 'file', $filePath = '', $fileName = '', $header = [])
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		// Add file as CURLFile
		$cfile = new \CURLFile($filePath, mime_content_type($filePath), $fileName);
		$postData[$fileField] = $cfile;

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

		// Set headers directly
		if ($header) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);

		return $result;
	}
}
