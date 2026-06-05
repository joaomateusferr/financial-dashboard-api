<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Services\AssetsClosePrice;

final class AssetsClosePriceTest extends TestCase {

    public function testConstructionInvalidDate(): void {

        $ExceptionString = '';

        try {

            $AssetsClosePrice =  new AssetsClosePrice(['2026-13-05' => ['AUVP11.SA', 'AUPO11.SA']]);

        } catch (Exception $Exception) {

            $ExceptionString = $Exception->getMessage();
        }

        $this->assertSame($ExceptionString, 'Invalid date (2026-13-05) on assets by date!');

    }

    public function testConstructionEmptyAssets(): void {

        $ExceptionString = '';

        try {

            $AssetsClosePrice =  new AssetsClosePrice(['2026-05-05' => []]);

        } catch (Exception $Exception) {

            $ExceptionString = $Exception->getMessage();

        }

        $this->assertSame($ExceptionString, 'Assets by date must contain at least one asset on at least one valid date!');

    }

    public function testFetchValidData(): void {

        $ExceptionString = '';

        try {

            $AssetsClosePrice =  new AssetsClosePrice(['2026-05-05' => ['AUVP11.SA', 'AUPO11.SA']]);
            $AssetsByDate = $AssetsClosePrice->fetch();

        } catch (Exception $Exception) {

            $ExceptionString = $Exception->getMessage();
        }

        $this->assertTrue(empty($ExceptionString));
        $this->assertSame($AssetsByDate, ['2026-05-05' => ['AUVP11.SA' => 105.33999633789062, 'AUPO11.SA' => 129.4499969482422]]);

    }

}
