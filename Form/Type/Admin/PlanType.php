<?php
/**
 * This file is part of payjp4
 *
 * Copyright(c) Akira Kurozumi <info@a-zumi.net>
 *
 *  https://a-zumi.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\payjp4\Form\Type\Admin;

use Eccube\Common\EccubeConfig;
use Payjp\Payjp;
use Plugin\payjp4\Entity\Payjp\Plan;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PlanType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    private $eccubeConfig;

    public function __construct(
        EccubeConfig $eccubeConfig
    )
    {
        $this->eccubeConfig = $eccubeConfig;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', IntegerType::class, [
                'label' => '金額',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('charge_interval', ChoiceType::class, [
                'label' => '課金間隔',
                'choices' => [
                    '月次課金' => 'month',
                    '年次課金' => 'year'
                ],
                'expanded' => false
            ])
            ->add('name', TextType::class, [
                'label' => 'プラン名',
            ])
            ->add('trial_days', ChoiceType::class, [
                'label' => 'トライアル日数',
                'choices' => array_combine(range(0, 365), range(0, 365)),
                'expanded' => false
            ])
            ->add('billing_day', ChoiceType::class, [
                'label' => '課金日',
                'required' => false,
                'choices' => array_combine(range(1, 31), range(1, 31))
            ]);

        $builder
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();

                if (!$data instanceof Plan) {
                    return;
                }

                if (!$form->isValid()) {
                    return;
                }

                try {
                    Payjp::setApiKey($this->eccubeConfig['payjp_secret_key']);
                    $plan = \Payjp\Plan::create([
                        'amount' => $data->getAmount(),
                        'currency' => $data->getCurrency(),
                        'interval' => $data->getChargeInterval(),
                        'name' => $data->getName(),
                        'trial_days' => $data->getTrialDays(),
                        'billing_day' => $data->getBillingDay()
                    ]);

                    $data->setPlanId($plan->id);
                    $data->setCreated(new \DateTime($plan->created));

                } catch (\Exception $e) {
                    $form->get('amount')->addError(new FormError($e->getMessage()));
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Plan::class,
        ]);
    }
}
