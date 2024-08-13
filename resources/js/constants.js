export const ERROR_MESSAGES = {
    registrationNameError: 'only letters, spaces, and hyphens are allowed',
    passwordError: 'passwords must match',
    logoutError: 'Logout failed:',
    dateFormatError: 'Invalid date format.',
    dateRangeError: 'The selected date is not in the provided range.',
    quantityError: 'Wrong input for product quantity.',
    quantityRangeError: 'You can not order more then 50 items from one product.',
    removeProduct: 'The following product(s) will be removed from your cart:\n',
    clearCart: 'Fix the input or clear cart and start again if you want to proceed with the order.',
    chooseDelivery: 'Please choose a delivery type to continue to payment.',
    selectAddress: 'Please select a delivery address to proceed with payment.',
    zipError: 'it is not a valid zip code',
    formatError: 'invalid format',
    addressType: 'Please chose from the options for us, how to handle the provided address.',
    addAddress: 'Fill out the form and press save address to proceed with payment!',
    cardNumber: 'Card number is a required field',
    cardNumberFormat: 'Card number is not valid',
    cvc: 'CVC is a required field',
    cvcFormat: 'CVC must be 3 digits',
    requiredFields: 'All fields are required',
    wrongDate: 'Invalid date format',
    stripeInitError: 'Failed to initialize Stripe elements',
    stripeError: 'An error occurred while processing the payment',
    choosePayment: 'Please choose a payment option to continue to checkout.',
    payWithStripe: 'Proceed with online payment to continue to checkout.',
    failedOrder: 'An unexpected error occurred. Please try again.',
    unexpectedError: 'An unexpected error occurred.',
    noCardElement: 'Card element not found.',
}

export const SUCCESS_MESSAGES = {
    orderSuccess: 'Your order has been successfully placed! Thank you for shopping from PROOFED.',
    loginSuccess: 'Welcome on PROOFED!',
}

export const FILES = {
    placeholderImagePath: '/images/product_placeholder_image.png',
    backgroundImagePath: '/images/background.jpg',
    cartImage: '/images/cart_icon.png',
}

export const BUTTONS = {
    registration: 'Registration',
    login: 'Log in',
    showProducts: 'Show products',
    addToCart: 'Add to cart',
    cartAlt: 'Cart,',
    yes: 'Yes',
    no: 'No',
    x: 'X',
    clearCart: 'Clear cart',
    back: 'Go back',
    forth: 'Next',
    addAddress: 'New address',
    saveAddress: 'Save address',
    placeOrder: 'Place order',
    googleSingUp: 'Sign up with Google',
    googleSingIn: 'Sign in with Google',
    proceedPayment: 'Pay',
}

export const STYLES = {
    darkGrey: '#3a3d44',
    lightGrey: 'rgba(111, 112, 119, 0.95)',
    lightGreyTrans: 'rgba(111, 112, 119, 0.2)',
    border: 'solid 5px #3a3d44',
    modalImgContainer: 'rgba(0, 0, 0, 0.6)',
    modalContainer: 'rgba(0, 0, 0, 0.4)',
    warningYellow: '#ffc107',
    inputYellow: '#f7fab2',
    successGreen: '#2b702b',
}

export const LINKS = {
    home: '/',
    logout: '/logout',
    profile: '/profile',
    login: '/login',
    registration: '/registration',
    products: '/products',
    order: '/order',
    cart: '/cart',
    userAddresses: '/user_addresses',
    googleSignUp: '/auth/google/register',
    googleSignIn: '/auth/google/login',
    stripePayment: '/create-payment-intent',
}
