@echo off
echo Setting up Hindu Takht React Native App...

REM Create new React Native project
echo Creating React Native project...
npx react-native init hindutakhtMobile

REM Navigate to project directory
cd hindutakhtMobile

REM Install required dependencies
echo Installing dependencies...
npm install @react-navigation/native @react-navigation/bottom-tabs @react-navigation/stack
npm install react-native-screens react-native-safe-area-context
npm install react-native-gesture-handler react-native-reanimated
npm install @react-native-async-storage/async-storage
npm install axios react-native-vector-icons
npm install react-native-image-picker

REM Install iOS specific dependencies (if on macOS)
if exist "ios" (
    echo Installing iOS dependencies...
    cd ios && pod install && cd ..
)

echo Setup complete! 
echo To run the app:
echo 1. Start your Laravel backend with "php artisan serve"
echo 2. Navigate to the hindutakhtMobile directory
echo 3. Run "npx react-native run-android" (or "npx react-native run-ios" on macOS)
echo.
echo Press any key to close this window...
pause