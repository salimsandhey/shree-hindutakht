@extends('layouts.app')

@section('title', 'Privacy Policy - Shree Hindutakht')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-md p-6 md:p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6 text-center">Privacy Policy</h1>
        
        <div class="prose prose-lg max-w-none">
            <p class="text-gray-600 mb-6"><strong>Last Updated:</strong> {{ date('F d, Y') }}</p>
            
            <p class="mb-6">Shree Hindutakht ("we," "our," or "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our mobile application and website.</p>
            
            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">1. Information We Collect</h2>
            
            <h3 class="text-xl font-semibold text-gray-800 mt-6 mb-3">Personal Information</h3>
            <p class="mb-4">We may collect personally identifiable information that you voluntarily provide, including:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Name</li>
                <li>Email address</li>
                <li>Phone number</li>
                <li>Address</li>
                <li>Photographs for ID card generation</li>
                <li>Membership information</li>
                <li>Payment information (processed securely through our payment partners)</li>
            </ul>
            
            <h3 class="text-xl font-semibold text-gray-800 mt-6 mb-3">Usage Information</h3>
            <p class="mb-4">We automatically collect information about your interaction with our app, including:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Device information (model, operating system, etc.)</li>
                <li>App usage statistics</li>
                <li>IP address</li>
                <li>Browser type and version</li>
                <li>Pages viewed and time spent</li>
            </ul>
            
            <h3 class="text-xl font-semibold text-gray-800 mt-6 mb-3">Location Information</h3>
            <p class="mb-4">We may collect information about your location when you use our app features that require location services, such as event check-ins.</p>
            
            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">2. How We Use Your Information</h2>
            <p class="mb-4">We use the information we collect for various purposes, including:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>To provide and maintain our services</li>
                <li>To process membership applications and manage member accounts</li>
                <li>To communicate with you about events, updates, and announcements</li>
                <li>To process donations and payments</li>
                <li>To generate and manage ID cards</li>
                <li>To improve our app and user experience</li>
                <li>To detect, prevent, and address technical issues</li>
                <li>To comply with legal obligations</li>
            </ul>
            
            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">3. Information Sharing and Disclosure</h2>
            <p class="mb-4">We may share your information in the following situations:</p>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>With Service Providers:</strong> We may share your information with third-party vendors who perform services on our behalf.</li>
                <li><strong>For Legal Reasons:</strong> We may disclose your information if required by law or in response to valid legal requests.</li>
                <li><strong>Business Transfers:</strong> In connection with any merger, sale of company assets, or acquisition.</li>
                <li><strong>With Your Consent:</strong> We may share your information with your consent or at your direction.</li>
            </ul>
            
            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">4. Data Security</h2>
            <p class="mb-4">We implement appropriate security measures to protect your personal information. However, no method of transmission over the Internet or electronic storage is 100% secure.</p>
            
            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">5. Data Retention</h2>
            <p class="mb-4">We retain your personal information for as long as necessary to fulfill the purposes outlined in this Privacy Policy, unless a longer retention period is required by law.</p>
            
            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">6. Your Rights</h2>
            <p class="mb-4">Depending on your location, you may have certain rights regarding your personal information, including:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>The right to access, update, or delete your information</li>
                <li>The right to object to processing</li>
                <li>The right to data portability</li>
                <li>The right to withdraw consent</li>
            </ul>
            <p class="mb-4">To exercise these rights, please contact us at the information provided below.</p>
            
            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">7. Children's Privacy</h2>
            <p class="mb-4">Our services are not intended for children under 13 years of age. We do not knowingly collect personal information from children under 13.</p>
            
            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">8. Changes to This Privacy Policy</h2>
            <p class="mb-4">We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last Updated" date.</p>
            
            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">9. User Experience and Navigation</h2>
            <p class="mb-4">For a better user experience, our application automatically redirects logged-in members to their personalized home page when they attempt to access public pages. This ensures that authenticated users can quickly access member-specific content and features without unnecessary navigation steps.</p>
            
            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">10. Account Deletion</h2>
            <p class="mb-4">You have the right to request the deletion of your account and associated personal data. To request account deletion, please contact us using the information provided in the "Contact Us" section below. Upon receiving your request, we will:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Verify your identity to prevent unauthorized account deletion</li>
                <li>Process your request within 30 days of verification</li>
                <li>Send you a confirmation once your account has been deleted</li>
            </ul>
            
            <h3 class="text-xl font-semibold text-gray-800 mt-6 mb-3">Data Deletion Process</h3>
            <p class="mb-4">When you request account deletion, we will permanently remove the following personal information:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Your profile information (name, email, phone number, address)</li>
                <li>Your uploaded photographs and ID card data</li>
                <li>Your membership information</li>
                <li>Your posts and comments</li>
                <li>Your event RSVPs and participation records</li>
                <li>Your donation history</li>
                <li>Your communication preferences</li>
            </ul>
            
            <h3 class="text-xl font-semibold text-gray-800 mt-6 mb-3">Data Retention</h3>
            <p class="mb-4">Notwithstanding your deletion request, we may retain certain information as required by law or for legitimate business purposes:</p>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Legal Compliance:</strong> We retain transaction records and financial data for 7 years to comply with tax and auditing requirements</li>
                <li><strong>Security:</strong> We retain logs and security-related data for 2 years to prevent fraud and ensure platform security</li>
                <li><strong>Dispute Resolution:</strong> We retain necessary information for 3 years to resolve potential disputes</li>
                <li><strong>Aggregated Analytics:</strong> We may retain anonymized, aggregated data indefinitely for analytical purposes, which does not identify individual users</li>
            </ul>
            
            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">11. Contact Us</h2>
            <p class="mb-4">If you have any questions about this Privacy Policy, please contact us:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>By email: privacy@nocollarmedia.com</li>
                <li>By visiting our website: https://nocollarmedia.com</li>
            </ul>
            
            <div class="mt-8 p-4 bg-gray-100 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Additional Information</h3>
                <p class="mb-3 text-gray-700">For information about our child safety standards and practices, please visit our <a href="{{ route('safety.standards') }}" class="text-orange-600 hover:text-orange-700 font-medium">Safety Standards</a> page.</p>
            </div>
            
            <div class="mt-10 p-4 bg-blue-50 rounded-lg">
                <p class="text-blue-800"><strong>Note:</strong> This privacy policy is tailored specifically for the Shree Hindutakht mobile application and website. It reflects our commitment to protecting your personal information while providing you with valuable services related to Hindu community activities and events.</p>
            </div>
        </div>
    </div>
</div>
@endsection