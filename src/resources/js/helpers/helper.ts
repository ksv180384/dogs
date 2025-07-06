export const isImageUrl = (url: string): boolean => {
  const imageExtensionRegex = /\.(jpeg|jpg|gif|png|webp|svg)$/i;

  try {
    const parsedUrl = new URL(url);
    return imageExtensionRegex.test(parsedUrl.pathname);
  } catch (e) {
    if (e instanceof TypeError) {
      // Handle invalid URLs (when URL constructor throws TypeError)
      return false;
    }
    // For any other unexpected errors, you might want to log them
    console.error('Unexpected error while checking image URL:', e);
    return false;
  }
};
