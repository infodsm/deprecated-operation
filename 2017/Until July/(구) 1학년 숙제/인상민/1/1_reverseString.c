#include <stdio.h>
#include <string.h>

int main(void) {
    int i, len;
    char string[1000];
    gets(string);
    len = strlen(string);
    for (i = len - 1; i > -1; i--)
        printf("%c", string[i]);
    return 0;
}
