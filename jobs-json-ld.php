<?php

$jsonLd = [];
$jsonLd['@context'] = "http://schema.org/";
$jsonLd["@type"] = "JobPosting";
$jsonLd["title"] = "Web Designer";
$jsonLd["description"] = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris risus purus, suscipit sit amet commodo non, aliquet nec augue. Nulla enim est, ornare nec diam vel, congue fringilla neque. Suspendisse ac pretium nulla, eu gravida urna. Vivamus nec sodales turpis.";
$jsonLd["identifier"] = [
	 "@type" => "PropertyValue",
     "name" =>  "Neon Web Developer",
     "value" =>"WD"
];

$jsonLd["datePosted"] = "2022-09-06";
$jsonLd["validThrough"] = "2022-09-20";
$jsonLd["employmentType"] = "FULL_TIME"; //PART_TIME, CONTRACTOR, TEMPORARY, INTERN, VOLUNTEER, PER_DIEM, 
$jsonLd["hiringOrganization"] = [
        "@type"  => "Organization",
        "name"   => "Neon Web Developer",
        "sameAs" => "https://neon.web.id"
];

$jsonLd["jobLocation"] = [
        "address": [
            "@type" => "PostalAddress",
            "streetAddress" => "Jl. Raya Slamet Riyadi No. 1",
            "addressLocality" => "Surakarta",
            "addressRegion" => "Jawa Tengah",
            "postalCode" => "123456",
            "addressCountry" => "Indonesia"
        ]
];

$jsonLd["baseSalary"] = [
        "@type" => "MonetaryAmount",
        "currency" => "IDR",
        "value": [
            "@type": "QuantitativeValue",
            "value": "Negotiable",
            "unitText": "MONTH" //HOUR, WEEK, MONTH, YEAR
        ]
];

echo "<script type='application/ld+json'>" . json_encode($jsonLd, 128) . "</script>";
