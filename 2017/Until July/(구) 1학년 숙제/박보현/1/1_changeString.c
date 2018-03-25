#include <stdio.h>

int main() {
    char a[50], b[3] = {'e', 'l', '\0'}, c[3] = {'a', 'b', '\0'}, i = 0;

    printf("문자열 입력 : ");
    scanf("%s", a);

    while (i < 50) {
        if (a[i] == b[0]) {
            if (a[i + 1] == b[1]) {
                a[i] = c[0];
                a[i + 1] = c[1];
            }
        }
        i++;
    }
    printf("%s\n", a);
    return 0;
}
