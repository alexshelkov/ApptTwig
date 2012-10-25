<?php
return array(
    'appt' => array(
        'twig' => array(
            'extension_manager' => array(
                'factories' => array (
                    'ZendViewHelpers' => 'ApptTwig\Service\Extension\ZendViewHelpersFactory'
                )
            ),
        )
    )
);
