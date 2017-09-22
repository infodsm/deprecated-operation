#include <stdio.h>
#include <string.h>

void change(char string[], char before[], char after[]) {
    int slen = strlen(string), blen = strlen(before), i, j, k, cmp;
    for (i = 0; i < slen - blen + 1; i++) {
        cmp = 0;
        for (j = i, k = 0; j < i + blen; j++, k++)
            if (string[j] == before[k])
                cmp++;
        if (cmp == blen)
            for (j = i, k = 0; j < i + blen; j++, k++)
                string[j] = after[k];
    }
    printf("%s", string);
}

int main(void) {
    char string[1000], before[100], after[100];
    scanf("%s %s %s", string, before, after);
    change(string, before, after);
    return 0;
}
