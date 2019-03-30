#include <stdio.h>      /* printf, fgets */
#include <stdlib.h>     /* atoi */
#include <string.h>     /* strlen */

const int LETTERS_NUM = 26;
const char BASE_LETTER = 'a';

int main(int argc, char const *argv[])
{
    int characters[LETTERS_NUM] = {0};
    const char *text = argv[1]; 
    int i = 0;

    if(argc < 2){
        printf("%s\n", "Usage:\n\t./a.out string");
        return 1;
    }
    
    while(i < strlen(text))
    {
        characters[text[i] - BASE_LETTER]++;
        i++;
    }

    for(int i = 0; i < LETTERS_NUM; i++)
    {
        if(characters[i] > 0){
            printf("There was %d occurences of letter %c in %s.\n", characters[i], i + BASE_LETTER, text);
        }
    }
    
    return 0;
}
