#include <stdio.h>

int main(int argc, char const *argv[])
{
    for(int i = 1; i <= 100; i++)
    {
        if (i % 3 != 0 && i % 5 != 0) {
            printf("%d ", i);
            continue;
        }
        if (i % 3 == 0) {
            printf("%s ", "Fizz");
        }
        if (i % 5 == 0) {
            printf("%s ", "Buzz");
        }        
    }
    return 0;
}
