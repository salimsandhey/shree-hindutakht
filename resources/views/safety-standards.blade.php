@extends('layouts.app')

@section('title', 'Safety Standards - Shree Hindutakht')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-md p-6 md:p-8">
        <h1 class="text-3xl font-bold text-primary text-center mb-6 pb-4 border-b-2 border-primary">Shree Hindutakht Safety Standards</h1>
        
        <p class="text-gray-600 mb-6"><strong>Last Updated:</strong> {{ date('F d, Y') }}</p>
        
        <p class="mb-6 text-gray-700">Shree Hindutakht is committed to providing a safe and respectful environment for all users. We have implemented comprehensive safety measures to prevent and address child sexual abuse and exploitation (CSAE) in accordance with global standards and applicable laws.</p>
        
        <h2 class="text-2xl font-semibold text-primary mt-8 mb-4">Child Safety Policy</h2>
        
        <h3 class="text-xl font-semibold text-gray-800 mt-6 mb-3">1. Prohibition of CSAE Content</h3>
        <ul class="list-disc pl-6 mb-4 text-gray-700">
            <li>Shree Hindutakht strictly prohibits any content that depicts or promotes child sexual abuse or exploitation</li>
            <li>All users must be 13 years of age or older to use the platform</li>
            <li>We employ automated systems and human review to detect and remove prohibited content</li>
            <li>Any suspected CSAE content is immediately removed and reported to appropriate authorities</li>
        </ul>
        
        <h3 class="text-xl font-semibold text-gray-800 mt-6 mb-3">2. Age Verification and Registration</h3>
        <ul class="list-disc pl-6 mb-4 text-gray-700">
            <li>Users must provide accurate age information during registration</li>
            <li>Accounts suspected of being operated by minors under 13 are subject to verification or removal</li>
            <li>Parents or guardians can request account information or deletion for minors through our contact channels</li>
        </ul>
        
        <h3 class="text-xl font-semibold text-gray-800 mt-6 mb-3">3. Reporting Mechanisms</h3>
        <ul class="list-disc pl-6 mb-4 text-gray-700">
            <li>All users can report suspicious content or behavior through in-app reporting tools</li>
            <li>Reports are reviewed by our moderation team within 24 hours</li>
            <li>Emergency reports are prioritized and addressed immediately</li>
            <li>Users can report concerns to our dedicated safety team via email</li>
        </ul>
        
        <h3 class="text-xl font-semibold text-gray-800 mt-6 mb-3">4. Content Moderation</h3>
        <ul class="list-disc pl-6 mb-4 text-gray-700">
            <li>Automated systems scan all uploaded content for prohibited material</li>
            <li>Human moderators review flagged content for context and compliance</li>
            <li>Machine learning algorithms are continuously updated to detect new forms of prohibited content</li>
            <li>Regular audits ensure compliance with safety standards</li>
        </ul>
        
        <h3 class="text-xl font-semibold text-gray-800 mt-6 mb-3">5. User Education</h3>
        <ul class="list-disc pl-6 mb-4 text-gray-700">
            <li>Safety guidelines are prominently displayed during registration</li>
            <li>Regular safety tips are shared through community notifications</li>
            <li>Users are educated about recognizing and reporting suspicious behavior</li>
        </ul>
        
        <h3 class="text-xl font-semibold text-gray-800 mt-6 mb-3">6. Law Enforcement Cooperation</h3>
        <ul class="list-disc pl-6 mb-4 text-gray-700">
            <li>Shree Hindutakht cooperates fully with law enforcement agencies investigating CSAE</li>
            <li>We preserve relevant data for legal investigations as required by law</li>
            <li>Emergency disclosure procedures are in place for time-sensitive cases</li>
        </ul>
        
        <h3 class="text-xl font-semibold text-gray-800 mt-6 mb-3">7. Staff Training</h3>
        <ul class="list-disc pl-6 mb-4 text-gray-700">
            <li>All staff members receive regular training on CSAE identification and response</li>
            <li>Moderators are trained to recognize and escalate potential CSAE cases</li>
            <li>Management is trained on legal obligations and reporting procedures</li>
        </ul>
        
        <h2 class="text-2xl font-semibold text-primary mt-8 mb-4">Compliance with International Standards</h2>
        <p class="mb-4 text-gray-700">Shree Hindutakht complies with international standards and frameworks including:</p>
        <ul class="list-disc pl-6 mb-4 text-gray-700">
            <li>UNICEF's Five Strategies to End Child Sexual Exploitation, Abuse and Harassment Online</li>
            <li>WePROTECT Global Alliance commitments</li>
            <li>Applicable national and regional laws regarding online child protection</li>
        </ul>
        
        <h2 class="text-2xl font-semibold text-primary mt-8 mb-4">Contact Information</h2>
        <div class="bg-gray-100 p-4 rounded-lg mb-6">
            <p class="text-gray-800"><strong>Designated Safety Contact:</strong> Abubakar Akhlaq</p>
            <p class="text-gray-800"><strong>Email:</strong> abubakar.akhlaq@gmail.com</p>
            <p class="text-gray-800"><strong>Response Time:</strong> Within 24 hours for standard inquiries, immediate for emergency reports</p>
        </div>
        
        <p class="mb-6 text-gray-700">Our designated point of contact is ready and able to speak about our app's child sexual abuse material (CSAM) prevention practices and compliance with relevant laws and policies.</p>
        
        <h2 class="text-2xl font-semibold text-primary mt-8 mb-4">Reporting Requirements</h2>
        <p class="mb-4 text-gray-700">Shree Hindutakht complies with all relevant child safety laws and reports to regional and national authorities as required. We maintain detailed logs of all safety-related actions and cooperate fully with investigations.</p>
        
        <h2 class="text-2xl font-semibold text-primary mt-8 mb-4">Policy Updates</h2>
        <p class="mb-4 text-gray-700">This safety policy is reviewed quarterly and updated as needed to maintain compliance with evolving standards and legal requirements. Users are notified of significant changes through in-app notifications and email.</p>
        
        <div class="mt-10 p-4 bg-blue-50 rounded-lg text-center">
            <p class="text-blue-800"><strong>Note:</strong> For immediate safety concerns, contact local authorities or national child protection hotlines.</p>
        </div>
    </div>
</div>
@endsection