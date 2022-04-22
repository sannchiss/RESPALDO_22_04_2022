<?php

namespace FedExVendor\FedEx\PickupService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * RequestedShippingDocumentType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Pickup Service
 */
class RequestedShippingDocumentType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _CERTIFICATE_OF_ORIGIN = 'CERTIFICATE_OF_ORIGIN';
    const _COMMERCIAL_INVOICE = 'COMMERCIAL_INVOICE';
    const _CUSTOMER_SPECIFIED_LABELS = 'CUSTOMER_SPECIFIED_LABELS';
    const _CUSTOM_PACKAGE_DOCUMENT = 'CUSTOM_PACKAGE_DOCUMENT';
    const _CUSTOM_SHIPMENT_DOCUMENT = 'CUSTOM_SHIPMENT_DOCUMENT';
    const _DANGEROUS_GOODS_SHIPPERS_DECLARATION = 'DANGEROUS_GOODS_SHIPPERS_DECLARATION';
    const _EXPORT_DECLARATION = 'EXPORT_DECLARATION';
    const _FREIGHT_ADDRESS_LABEL = 'FREIGHT_ADDRESS_LABEL';
    const _GENERAL_AGENCY_AGREEMENT = 'GENERAL_AGENCY_AGREEMENT';
    const _LABEL = 'LABEL';
    const _NAFTA_CERTIFICATE_OF_ORIGIN = 'NAFTA_CERTIFICATE_OF_ORIGIN';
    const _OP_900 = 'OP_900';
    const _PENDING_SHIPMENT_EMAIL_NOTIFICATION = 'PENDING_SHIPMENT_EMAIL_NOTIFICATION';
    const _PRO_FORMA_INVOICE = 'PRO_FORMA_INVOICE';
    const _RETURN_INSTRUCTIONS = 'RETURN_INSTRUCTIONS';
}
