#include <stdio.h>

int main() {
    char a[6] = {'A', 'p', 'p', 'l', 'e', '\0'}, b[6] = {'\0'};
    int i = 0;

    for (i = 0; i < 5; i++) {
        b[i] = a[4 - i];
    }
    printf("%c", b[0]);
    printf("%c", b[1]);
    printf("%c", b[2]);
    printf("%c", b[3]);
    printf("%c\n", b[4]);
    printf("%s\n", b);

    return 0;
}
