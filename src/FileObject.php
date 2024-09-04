<?php

namespace LireinCore\YMLParser;

class FileObject extends \SplFileObject
{
    /**
     * @param int $charIndex
     * @param string $encoding
     * @return void
     */
    public function seekByCharIndex($charIndex, $encoding)
    {
        if ($charIndex >= 0) {
            $byteCount = 0;
            while (!$this->eof()) {
                $this->fseek(1, \SEEK_CUR);
                $byteCount++;

                $beforeReadOffset = $this->ftell();
                $chars = $this->fread($byteCount);
                $this->fseek($beforeReadOffset);

                if (\mb_strlen($chars, $encoding) >= $charIndex && \mb_check_encoding($chars, $encoding)) {
                    break;
                }
            }
        } else {
            $byteCount = 0;
            while ($this->ftell() > 0) {
                $this->fseek(-1, \SEEK_CUR);
                $byteCount++;

                $beforeReadOffset = $this->ftell();
                $chars = $this->fread($byteCount);
                $this->fseek($beforeReadOffset);

                if (\mb_strlen($chars, $encoding) >= \abs($charIndex) && \mb_check_encoding($chars, $encoding)) {
                    break;
                }
            }
        }
    }

    /**
     * @param int $length
     * @param string $encoding
     * @return string
     */
    public function readChars($length, $encoding)
    {
        $result = '';
        $readChars = 0;

        while ($readChars < $length && !$this->eof()) {
            $char = $this->fread(1);
            $result .= $char;
            if (\mb_check_encoding($result, $encoding)) {
                $readChars++;
            }
        }

        return $result;
    }
}
