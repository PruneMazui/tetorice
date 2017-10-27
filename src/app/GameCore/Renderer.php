<?php
namespace PruneMazui\Tetrice\GameCore;


use PruneMazui\Tetrice\GameCore\Tile\TileWhite;
use PruneMazui\Tetrice\GameCore\Tile\AbstractTile;
use PruneMazui\Tetrice\GameCore\Tile\TileBlack;
use PruneMazui\Tetrice\GameCore\Tetoriminone\AbstractTetoriminone;

class Renderer
{

/* こんな感じ
 * ■■■■■■■■■■■■■■
 * ■■■■■TETRICE ■■■■■
 * ■■■■■■■■■■■■■■
 * ■□                    □■
 * ■□                    □■
 * ■□                    □■
 * ■□                    □■
 * ■□                    □■
 * ■□                    □■
 * ■□                    □■
 * ■□       ここが       □■
 * ■□                    □■
 * ■□       Feildの      □■
 * ■□                    □■
 * ■□       縦横         □■
 * ■□                    □■
 * ■□                    □■
 * ■□                    □■
 * ■□                    □■
 * ■□                    □■
 * ■□                    □■
 * ■□                    □■
 * ■□                    □■
 * ■□□□□□□□□□□□□■
 * ■■■■■■■■■■■■■■
 */

    /**
     * タイトル
     * マルチバイト禁止で・・・
     * @var string
     */
    private $title = 'TETRICE';

    private $previous = "";

    public function __construct()
    {
        // タイトルの文字数を偶数化しておく
        if (strlen($this->title) % 2 === 1) {
            $this->title .= ' ';
        }

        system('clear');
    }

    /**
     * 描画
     * @param Field $field
     * @param AbstractTetoriminone nullable $tetoriminone
     */
    public function render(Field $field, $tetoriminone)
    {
        $feild_width = $field->getWidth();

        $fillWhite = function () use ($feild_width) {
            $ret = "";
            for ($i = 0; $i < ($feild_width + 4); $i++) {
                $ret .= TileWhite::getInstance();
            }
            return $ret . "\n";
        };

        // 先頭3行
        $output = $fillWhite();
        $output .= $this->makeTitleLine($feild_width);
        $output .= $fillWhite();

        // フィールド
        $output .= $this->makeFeild($field, $tetoriminone);
        $output .= $fillWhite();

        // 描画
        $this->rewindCursor();
        echo $output;
    }

    /**
     * タイトル行を作成
     * @param int $feild_width
     * @return string
     */
    private function makeTitleLine($feild_width)
    {
        $ret = "";
        $width = $feild_width + 4;

        // 空白間隔を決める
        // 2文字で幅1と換算
        $start = intval(($width - strlen($this->title) / 2) / 2);

        for ($i = 0; $i < $start; $i++) {
            $ret .= TileWhite::getInstance();
        }
        $ret .= TileWhite::getInstance()->make($this->title, [1, 30]); // 太字、下線、黒のシーケンス

        // 残りを黒で埋める
        $remain = $width - ($start +  strlen($this->title) / 2);

        for ($i = 0; $i < $remain; $i++) {
            $ret .= TileWhite::getInstance();
        }
        return $ret . "\n";
    }

    private function makeFeild(Field $field, $tetoriminone)
    {
        $ret = "";

        $map = $field->getMap();
        if ($tetoriminone instanceof AbstractTetoriminone) {
            foreach ($tetoriminone->getCoordinates() as $coordinate) {
                list($x, $y) = $coordinate;
                if ($y < 0) {
                    continue;
                }

                $map[$y][$x] = $tetoriminone->getTile();
            }
        }

        foreach ($map as $line) {
            $ret .= TileWhite::getInstance() . TileBlack::getInstance();
            foreach ($line as $col) {
                if ($col instanceof AbstractTile) {
                    $ret .= $col;
                } else {
                    $ret .= TileWhite::getInstance();
                }
            }
            $ret .= TileBlack::getInstance() . TileWhite::getInstance() . "\n";
        }

        // 底
        $ret .= TileWhite::getInstance() . TileBlack::getInstance();
        for ($i = 0; $i < $field->getWidth(); $i++) {
            $ret .= TileBlack::getInstance();
        }
        $ret .= TileBlack::getInstance() . TileWhite::getInstance() . "\n";

        return $ret;
    }

    /**
     * ターミナルのカーソルを先頭に戻す
     * @param Field $field
     */
    private function rewindCursor()
    {
        echo "\e[2;0H";
    }
}
