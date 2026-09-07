<?php

    function renderIndicatorBadge(string $indicatorType, string $indicatorName): array
    {
        $hash = crc32($indicatorType . '|' . $indicatorName);
        $isGain = ($hash % 2) === 0;
        $percentage = number_format((($hash % 1250) / 100) + 0.5, 2, ',', '.');

        return [
            'class' => $isGain ? 'text-bg-success' : 'text-bg-danger',
            'icon' => $isGain ? '▲' : '▼',
            'label' => ($isGain ? '+' : '-') . $percentage . '%',
        ];
    }

    $Indicators = [
        'Brazilian fixed income' => [
            'CDI' => '14.15%',
            'Selic' => '14.25%',
            'IPCA' => '4.75%',
            'IPCA15' => '4.75%',
        ],
        'Cryptocurrencies' => [
            'Bitcoin-Dollars' => '$60,000.00',
            'Mayer Multiple' => '0.79'
        ],
        'Currency' => [
            'Dollar-Real' => 'R$5,18'
        ],
        'Extra' => [
            'BRENT' => 'US$ 76,49'
        ]
    ];

?>

<section class="container-fluid py-4">

    <div class="row g-4">

        <?php foreach ($Indicators as $IndicatorType => $IndicatorItems) { ?>

            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                    <h2 class="h5 mb-0"><?php echo htmlspecialchars($IndicatorType, ENT_QUOTES, 'UTF-8'); ?></h2>
                </div>

                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php foreach ($IndicatorItems as $IndicatorName => $IndicatorValue) { ?>
                        <?php $Badge = renderIndicatorBadge($IndicatorType, $IndicatorName); ?>
                        <div class="col">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-body p-4 d-flex flex-column gap-3">
                                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                                        <div class="flex-grow-1" style="min-width: 0;">
                                            <p class="text-body-secondary small text-uppercase fw-semibold mb-2">
                                                <?php echo htmlspecialchars($IndicatorName, ENT_QUOTES, 'UTF-8'); ?>
                                            </p>
                                            <h2 class="display-6 fw-semibold mb-0 lh-1 text-break">
                                                <?php echo htmlspecialchars($IndicatorValue, ENT_QUOTES, 'UTF-8'); ?>
                                            </h2>
                                        </div>

                                        <span class="badge <?php echo htmlspecialchars($Badge['class'], ENT_QUOTES, 'UTF-8'); ?> rounded-pill flex-shrink-0">
                                            <?php echo htmlspecialchars($Badge['icon'] . ' ' . $Badge['label'], ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center pt-1">
                                        <span class="small text-body-secondary">Atualizado agora</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

        <?php } ?>

    </div>

</section>
