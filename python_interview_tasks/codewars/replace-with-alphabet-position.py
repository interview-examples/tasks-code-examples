# In this kata you are required to, given a string, replace every letter with its position in the alphabet.
#
# If anything in the text isn't a letter, ignore it and don't return it.
#
# "a" = 1, "b" = 2, etc.

def alphabet_position(text):
    text = text.lower()
    return ' '.join(str(ord(c) - ord('a') + 1) for c in text if 'a' <= c <= 'z')
